<?php
/**
 * 
 * Model_ApeEvaluationVerificador
 * @uses  
 *
 */
class Model_ApeEvaluationVerificador
{

    public $DbApeEvaluationVerificador = "";
    
    public function __construct()
    {
        $this->DbAppraiser = DbTable_AppraiserEnterprise::getInstance();
        $this->DbChecker = DbTable_CheckerEnterprise::getInstance();
        $this->DbEnterprise = DbTable_Enterprise::getInstance();
        $this->DbApeEvaluation = DbTable_ApeEvaluation::getInstance();
        $this->DbCheckerEvaluation = DbTable_CheckerEvaluation::getInstance();
        $this->DbApeEvaluationVerificador = DbTable_ApeEvaluationVerificador::getInstance();         
    }
    
    public function getTable()
    {
        return $this->DbApeEvaluationVerificador;
    }
    
function getRespostaRelatoAutoAvaliacao($enterpriseId, $competitionId = null, $UserId)
    {
        if (!$competitionId) {
            $competitionId = Zend_Registry::get('configDb')->competitionId;
        }
        return $this->DbApeEvaluationVerificador->getRespostaRelatoAutoAvaliacao($enterpriseId, $competitionId, $UserId);
    }
    
    public function verificaResposta($enterpriseId,$perguntaId, $competitionId = null){
        if (!$competitionId) {
            $competitionId = Zend_Registry::get('configDb')->competitionId;
            
        }        
        return $this->DbApeEvaluationVerificador->verificaResposta($enterpriseId, $perguntaId,$competitionId);                
    }
    
    
    public function verificaRespostaCriterio($enterpriseId,$perguntaId, $competitionId = null){
        if (!$competitionId) {
            $competitionId = Zend_Registry::get('configDb')->competitionId;
            
        }
        
        return  $this->DbApeEvaluationVerificador->verificaRespostaCriterio($enterpriseId, $perguntaId,$competitionId);
        
    }

  public function getEnterpriseCheckerEnterprisePontosFortes($IdEntrepriseNacional, $CompetitionId)
  {
      return $this->DbApeEvaluationVerificador->getEnterpriseCheckerEnterprisePontosFortes($IdEntrepriseNacional, $CompetitionId);      
  }
  
  public function getAll($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->DbApeEvaluationVerificador->fetchAll($where, $order, $count, $offset);
    }
 
}
