<?php

namespace App\Presenters;

use Nette;


final class HomepagePresenter extends Nette\Application\UI\Presenter
{
    public function __construct()
    {
        $this->setDataByXmlFormat();
    }

    public function renderDefault()
    {
        $queryData = $this->getDataQuery();
        $this->template->data = $queryData;
    }


    public function getDataQuery()
    {
        $result = \dibi::query('SELECT * FROM zaznamy  ORDER BY datum DESC')->fetchAll();
        return $result;
    }

    public function setDataByXmlFormat()
    {
        $map_url = "https://www.3it.cz/test/data/xml";
        $xmlfile = simplexml_load_file($map_url);
        $xmlArray = $this->xml2array($xmlfile);
        foreach ($xmlArray as $recordList) {
            foreach ($recordList as $record) {
                $id = strval($record->ID);
                $name = strval($record->JMENO);
                $surname = strval($record->PRIJMENI);
                $date = strval($record->DATE);
                \dibi::query("INSERT INTO zaznamy", [
                    'id' => $id,
                    'jmeno' => $name,
                    'prijmeni' => $surname,
                    'datum' => $date
                ], 'ON DUPLICATE KEY UPDATE %a', [
                    'jmeno' => $name,
                    'prijmeni' => $surname,
                    'datum' => $date
                ]);
            }
        }
    }

    public function xml2array($xmlObject, $out = array())
    {
        foreach ((array)$xmlObject as $index => $node)
            $out[$index] = (is_object($node)) ? $this->xml2array($node) : $node;

        return $out;
    }
}
