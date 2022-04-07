<?php

use Phalcon\Mvc\Controller;


class IndexController extends Controller
{
    public function indexAction()
    {
        $this->assets->addCss("css/styles.css");
        //    if got post
        if ($this->request->isPost()) {
            $ob = new \App\Components\SearchComponent();
            $data = $ob->searchLocation($this->request->getPost()["location"]);
            $this->view->data = $data;
        }
    }
    public function cityAction()
    {
        $this->assets->addCss("css/styles.css");
        $getCity = explode("/", $this->request->getQuery()["_url"])[3];
        if (!isset($getCity) | strlen($getCity) == 0) {
            header("location:/index");
        }
        $ob = new \App\Components\SearchComponent();
        $data = $ob->getDetailsByCity($getCity);
        $forecastData = $ob->getForecastByCity($getCity);
        $this->view->data = $data;
        $this->view->forecastData = $forecastData;
        $this->view->currentCity = $getCity;

        //if got post
        if ($this->request->isPost()) {
            $date = $this->request->getPost()['date'];
            $historyData = $ob->getHistory($getCity, $date);
            $this->view->forecastData = $historyData;
            $this->view->date = $date;
        }
    }
    public function errorAction()
    {
    }
}
