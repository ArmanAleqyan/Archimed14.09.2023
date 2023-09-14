<?php

namespace App\Nova\Dashboards;

use Laravel\Nova\Cards\Help;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Dashboards\Main as Dashboard;

use App\Nova\Metrics\LoginCounts;
use App\Nova\Metrics\Plan;
use App\Nova\Metrics\SearchResultPlan;
use App\Nova\Metrics\AnalisePage ;
use App\Nova\Metrics\CategoryPage;


class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */


    public function cards()
    {
        return [
            new  LoginCounts,
            new Plan,
            new SearchResultPlan,
           new AnalisePage,
           new CategoryPage,
        ];
    }
    public  function label()
    {
        return 'Главная страница';
    }




}
