<?php

use App\Classes\Output\ResponseFactory;
use App\Classes\Formatter\Device\GpsDataShortFormFormatterHelper as FormatterShort;
use App\Classes\Formatter\Device\GpsDataFullFormFormatter as FormatterFull;
use App\Classes\Exceptions\ApiException;

class Gps_data extends BaseController
{
    /**
     * Gps_data constructor.
     */
    public function __construct()
    {
        parent::__construct();

        ini_set('memory_limit', '256M');
        $this->load->model('gps/gps_model', 'gps', true);
        $this->load->model('incident_manager/im_model', 'im', true);
    }

    public function getDeviceAdr()
    {
        if (!in_array($this->input->get("device"), array('atm', 'tso'))) {
            ResponseFactory::make($this->get_format(), $this->output)
                ->badResponse(2, "Нет обязательных атрибутов: device={atm, tso}", 400);
        }

        try {
            //сокращенная форма
            if ($this->input->get("reportType") == "short") {

                if ($this->input->get("device") == 'atm') {
                    $data = $this->gps->getAtmAdrShort($this->input->get("range"));
                } elseif ($this->input->get("device") == 'tso') {
                    $data = $this->gps->getTsoAdrShort($this->input->get("range"));
                }

                ResponseFactory::make($this->get_format(), $this->output)
                    ->goodResponse(FormatterShort::format($data, $this->get_format()));

            } else {

                if ($this->input->get("device") == 'atm') {
                    $data = $this->gps->getAtmAdr($this->input->get("range"));
                } elseif ($this->input->get("device") == 'tso') {
                    $data = $this->gps->getTsoAdr($this->input->get("range"));
                }

                ResponseFactory::make($this->get_format(), $this->output)
                    ->goodResponse(FormatterFull::format($data, $this->get_format()));

            }
        } catch (ApiException $e) {

            ResponseFactory::make($this->get_format(), $this->output)
                ->badResponse($e->getCode(), $e->getMessage(), $e->getHttpStatus());

        }
    }
}
    