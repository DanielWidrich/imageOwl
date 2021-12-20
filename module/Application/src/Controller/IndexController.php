<?php
namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Application\Service\Data;

class IndexController extends AbstractActionController
{
    private $data = [
        'jsonData' => [
            'tableName' => '',
            'rowData' => [],
        ],
    ];

    public function indexAction()
    {
        return new ViewModel();
    }

    public function solutionAction()
    {
        $dataObj = new Data();
        $this->data['jsonData'] = $dataObj->getTable();
        return new ViewModel(['data' => $this->data]);
    }

    public function reportAction()
    {
        $returnData = [];
        if(isset($_POST['formSubmit']) and (isset($_POST['avg0']) and $_POST['avg0'] !== 'Undefined')) {
            $returnData = $this->processData($_POST);
        }
        if(!$returnData){
            return new ViewModel(['error' => 'true']);
        }
        return new ViewModel(['data' => $returnData]);
    }

    public function getDataService()
    {
        return new \Application\Service\Data();
    }

    private function processData($data){
        $rowCount = $data['rowCount'];
        $masterArray = [];
        for($x=0; $x < $rowCount; $x++){
            $i = strval($x);
            $subArray = [];
            $subArray['r1'] = $data['r1'.$i];
            $subArray['r2'] = $data['r2'.$i];
            $subArray['r3'] = $data['r3'.$i];
            $subArray['avg'] = $data['avg'.$i];
            $subArray['sum'] = $data['sum'.$i];
            array_push($masterArray, $subArray);
        }
        if($this->writeJSON($masterArray)){
            return $masterArray;
        } else {
            return false;
        }
    }

    private function writeJSON($masterArray){
        $jsonString = json_encode($masterArray);
        return file_put_contents(__DIR__ . "/report.json", $jsonString);
    }
}
