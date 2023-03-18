<?php

namespace App\Http\Controllers;

use App\Models\ReportingData;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ReportingDataController extends Controller
{
    /**
     * @OA\Get(
     *      path="/reporting-data/publisher/{publisherId}/interval/{startDate}/{endDate}",
     *      operationId="getReportingDataByPublisherByInterval",
     *      tags={"ReportingData"},
     *      summary="reporting_data by $publisherId between $startDate and $endDate",
     *      description="Return reporting_data by $publisherId between $startDate and $endDate, currency can be specified, USD by default",
     *      @OA\Parameter(
     *          name="publisherId",
     *          description="Publisher's id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="startDate",
     *          description="From what date the results will be based on",
     *          required=true,
     *          in="path",
     *          example="2023-03-09T07:17:27Z",
     *          @OA\Schema(
     *              type="datetime"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="endDate",
     *          description="Until what date the results will be based on",
     *          required=true,
     *          in="path",
     *          example="2023-03-18T14:35:31Z",
     *          @OA\Schema(
     *              type="datetime"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="currency",
     *          description="Currency code of the revenues (USD by default)",
     *          required=false,
     *          in="query",
     *          example="USD",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="page",
     *          description="page number",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="perPage",
     *          description="number of element per page",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400 ,
     *          description="Bad Request",
     *      )
     *     )
     *
     * @param Request $request
     * @param int $publisherId
     * @param String $startDate
     * @param String $endDate
     * @return LengthAwarePaginator|JsonResponse
     */
    public function getReportingDataByPublisherByInterval(Request $request, int $publisherId, string $startDate, string $endDate): LengthAwarePaginator|JsonResponse
    {
        $currency = $request->input('currency', 'USD'); // query param, USD default value

        // try to convert date params to Date objects, throw error if wrong formats
        try {
            $startDate = Carbon::createFromDate($startDate);
            $endDate = Carbon::createFromDate($endDate);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        // use Eloquent to retrieve an array of ReportingData
        return ReportingData::query()
            ->select(
                'reporting_data.id', 'requests', 'impressions', 'clicks',
                DB::raw('round(revenues * currency_rate.rate, 2) revenues'),
                'currency_rate.code as currency', 'report_date', 'publisher_id')
            ->with('publisher:id,name')
            ->join('currency_rate', function ($join) use ($currency) {
                $join->where('currency_rate.code', '=', $currency);
            })
            ->where('publisher_id', $publisherId)
            ->whereBetween('report_date', [$startDate, $endDate])
            ->orderBy('report_date')
            ->paginate($request->input('perPage', 10));
    }

    /**
     * @OA\Get(
     *      path="/reporting-data/revenues/publisher/{publisherId}/interval/{startDate}/{endDate}",
     *      operationId="getTotalRevenuesForPublisherByInterval",
     *      tags={"ReportingData"},
     *      summary="sum of revenues for $publisherId between $startDate and $endDate",
     *      description="Return the sum of revenues for $publisherId between $startDate and $endDate currency can be specified, USD by default",
     *      @OA\Parameter(
     *          name="publisherId",
     *          description="Publisher's id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="startDate",
     *          description="From what date the results will be based on",
     *          required=true,
     *          in="path",
     *          example="2023-03-09T07:17:27Z",
     *          @OA\Schema(
     *              type="datetime"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="endDate",
     *          description="Until what date the results will be based on",
     *          required=true,
     *          in="path",
     *          example="2023-03-18T14:35:31Z",
     *          @OA\Schema(
     *              type="datetime"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="currency",
     *          description="Currency code of the revenues (USD by default)",
     *          required=false,
     *          in="query",
     *          example="USD",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400 ,
     *          description="Bad Request",
     *      )
     *     )
     *
     * @param Request $request
     * @param int $publisherId
     * @param string $startDate
     * @param string $endDate
     * @return JsonResponse
     */
    public function getTotalRevenuesForPublisherByInterval(Request $request, int $publisherId, string $startDate, string $endDate): JsonResponse
    {
        $currency = $request->input('currency', 'USD'); // query param, USD default value

        // try to convert date params to Date objects, throw error if wrong formats
        try {
            $startDate = Carbon::createFromDate($startDate);
            $endDate = Carbon::createFromDate($endDate);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        // raw SQL to get the sum of revenues for given publisher for specifier time interval
        return response()->json(DB::select('
                select p.id, p.name, round(sum(rd.revenues * cr.rate), 2) revenues, cr.code, :start_date start_date, :end_date end_date
                from reporting_data rd
                join publisher p on p.id = rd.publisher_id
                join currency_rate cr on cr.code = :currency_code
                where rd.publisher_id = :publisher_id
                and rd.report_date between :start_date_report and :end_date_report
                group by p.id, p.name, cr.code, start_date, end_date
            ',
            [
                'publisher_id' => $publisherId,
                'currency_code' => $currency,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'start_date_report' => $startDate,
                'end_date_report' => $endDate
            ]
        ));
    }
}
