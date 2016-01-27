<?php
/**
 * 
 * Model_Appraiser
 * @uses  
 *
 */
class Model_Appraiser
{
    public $DbAppraiser = "";
	
	private $appraiserID;
    
    public function __construct()
    {
        $this->DbAppraiser = DbTable_AppraiserEnterprise::getInstance();
        $this->DbChecker = DbTable_CheckerEnterprise::getInstance();
        $this->DbEnterprise = DbTable_Enterprise::getInstance();
        $this->DbApeEvaluation = DbTable_ApeEvaluation::getInstance();
        $this->DbCheckerEvaluation = DbTable_CheckerEvaluation::getInstance();
        $this->DbApeEvaluationVerificador = DbTable_ApeEvaluationVerificador::getInstance(); 
	    $this->DbApeEvaluationVerificadorComment = DbTable_ApeEvaluationVerificadorComment::getInstance();        
    }
    
    public function getTable()
    {
        return $this->DbAppraiser;
    }
    
    public function getQuestions($where = null)
    {
        return DbTable_AvaliacaoPerguntas::getInstance()->fetchAll($where);
    }
	
    private function setAppraiserId($appraiserID)
	{
		$this->appraiserID = $appraiserID;
	}
	
	public function getAppraiserId()
	{
		return $this->appraiserID;
	}

    public function createAppraiserToEnterprise($appraiserRow,$data)
    {
        $data = $this->_filterInputAppraiserToEnterprise($data)->getUnescaped();
        $appraiserEntRow = $this->DbAppraiser->createRow()
            ->setUserId($appraiserRow->getId())
            ->setEnterpriseId($data['enterprise_id'])
            ->setQuestionnaireId( 
                isset($data['enterprise_id']) ?
                    $data['enterprise_id'] : null
            );
        $appraiserEntRow->save();
        return array(
            'status' => true
        );
    }

     public function setAppraiserToEnterprise($data)
    {
        $enterpriseId   = $data['enterprise_id'];
        $appraiserId    = $data['appraiser_id'];
        $programaId     = $data['programa_id'];
        $tipo           = $data['tipo'];
        $id           = $data['id'];
        $this->DbAppraiser->getAdapter()->beginTransaction();
		
        try {
            $where = array('EnterpriseId=?'=>$enterpriseId,'UserId=?'=>$appraiserId,'ProgramaId=?'=>$programaId,'AppraiserTypeId IN (4,5,6)'=>'');
			$obj = $this->DbAppraiser->fetchRow($where);
			if ($obj){
				return array(
					'status' => false,
					'messageError'=>'Este avaliador já está selecionado para esta empresa'
				);
			}
            unset($obj);
			$where = array('EnterpriseId=?'=>$enterpriseId,'UserId=?'=>$appraiserId,'AppraiserTypeId=?'=>$tipo,'ProgramaId=?'=>$programaId);
			// Sandra $$$ deletar antes de gravar novamente as respostas

//                 if ( $appraiserId == '0' ) {
//                     $this->DbAppraiser->delete($where);
//                 } else {
//                     $obj = $this->DbAppraiser->fetchRow($where);
//                     if ($obj) {
//                         $appraiserEnt = $obj;
//                     } else {
//                         $appraiserEnt = $this->DbAppraiser->createRow();
//                     } 
                    
                    $this->DbAppraiser->delete($where);
                    $appraiserEnt = $this->DbAppraiser->createRow();
                    $appraiserEntRow = $appraiserEnt
                    	->setId($id)
                        ->setUserId($appraiserId)
                        ->setEnterpriseId($enterpriseId)
                        ->setAppraiserTypeId($tipo)
                        ->setProgramaId($programaId)
                        ;
					//print_r($appraiserEntRow);exit;
					$appraisserId = $appraiserEntRow->save();
                    $this->setAppraiserId($appraisserId);
//             }
            $this->DbAppraiser->getAdapter()->commit();
            return array(
                'status' => true
            );

        } catch (Vtx_UserException $e) {
            DbTable_Question::getInstance()->getAdapter()->rollBack();
            return array(
                'status' => false, 'messageError' => $e->getMessage()
            );
        } catch (Exception $e) {
            DbTable_Question::getInstance()->getAdapter()->rollBack();
            throw new Exception($e);
        }
    }


	public function setCheckerToEnterpriseVerificador($data)
    {
			
        $enterpriseId   = $data['enterprise_id'];
        $userId   		= $data['user_id'];
        $programaId     = $data['programa_id'];
        $tipo           = $data['tipo'];
		$status        = $data['status'];
		
		$tbCheckerLast =  $this->DbChecker->select()
		    ->distinct()
            ->setIntegrityCheck(false)
            ->from(array("ce" => "CheckerEnterprise"), array('*'))
			->where('UserId = ?', $userId)
			->where('EnterpriseId = ?', $enterpriseId)
			->order('Id DESC');
		$last = $this->DbChecker->fetchRow($tbCheckerLast);
		
		
		if(isset($last) && $status != "C"){
			if($last->getStatus() == $status && $last->getStatus() != NULL && $last->getStatus() != "IIII")
			{
				$status = $status . "I";
			}
		}
		

        $this->DbChecker->getAdapter()->beginTransaction();
        try {
            $where = array(
                'EnterpriseId=?' => $enterpriseId,
                'CheckerTypeId=?' => $tipo,
                'ProgramaId=?' => $programaId,
				'UserId=?' => $userId
            );
               // $this->DbChecker->delete($where);
                $obj = $this->DbChecker->fetchRow($where);
                $checkerEnt = ($obj)? $obj : $this->DbChecker->createRow();

                $checkerEntRow = $checkerEnt
                    ->setUserId($userId)
                    ->setEnterpriseId($enterpriseId)
                    ->setCheckerTypeId($tipo)
                    ->setProgramaId($programaId)
					->setStatus($status)
                ;
                $checkerEnt->save();

            $this->DbChecker->getAdapter()->commit();
            return array(
                'status' => true
            );
        } catch (Vtx_UserException $e) {
            DbTable_Question::getInstance()->getAdapter()->rollBack();
            return array(
                'status' => false, 'messageError' => $e->getMessage()
            );
        } catch (Exception $e) {
            DbTable_Question::getInstance()->getAdapter()->rollBack();
            throw new Exception($e);
        }
    }



    public function setCheckerToEnterprise($data)
    {
        $enterpriseId   = $data['enterprise_id'];
        $checkerId    = $data['checker_id'];
        $programaId     = $data['programa_id'];
        $tipo           = $data['tipo'];
        $this->DbChecker->getAdapter()->beginTransaction();
        try {
            $where = array(
                'EnterpriseId=?' => $enterpriseId,
                'CheckerTypeId=?' => $tipo,
                'ProgramaId=?' => $programaId
            );
            if ( $checkerId == '0' ) {
                $this->DbChecker->delete($where);
            } else {
                $obj = $this->DbChecker->fetchRow($where);
                $checkerEnt = ($obj)? $obj : $this->DbChecker->createRow();

                $checkerEntRow = $checkerEnt
                    ->setUserId($checkerId)
                    ->setEnterpriseId($enterpriseId)
                    ->setCheckerTypeId($tipo)
                    ->setProgramaId($programaId)
                ;
                $checkerEnt->save();
            }
            $this->DbChecker->getAdapter()->commit();
            return array(
                'status' => true
            );
        } catch (Vtx_UserException $e) {
            DbTable_Question::getInstance()->getAdapter()->rollBack();
            return array(
                'status' => false, 'messageError' => $e->getMessage()
            );
        } catch (Exception $e) {
            DbTable_Question::getInstance()->getAdapter()->rollBack();
            throw new Exception($e);
        }
    }

    public function getCheckerToEnterpriseVerificador($data)
    {
    		
    	$enterpriseId   = $data['enterprise_id'];
    	$userId   		= $data['appraiser_id'];
    	$nacional       = $data['nacional'];
    	// Sandra $$$ o id do appraiserenterprise precisa ser igual ao do checkerenterprise, senão não acha mais as resppstas do relato
    	$query =  $this->DbChecker->select()
    	->distinct()
    	->setIntegrityCheck(false)
    	->from(array("ce" => "CheckerEnterprise"), 'Id')
    	->where('UserId = ?', $userId)
    	->where('EnterpriseId = ?', $enterpriseId)
    	->where('CheckerTypeId = ?', $nacional)
    	->order('Id DESC');
    	$last = $this->DbChecker->fetchRow($query);
    	return $last;
    }
    
    public function insertAppraiserEnterpriseTransaction($appraiserRow, $data)
    {
        // start transaction externo
        Zend_Registry::get('db')->beginTransaction();
        try {
            
            // 1.1 Delete AppraiserEnterprise By Appraiser
            $this->deleteAppraiserEnterpriseByAppraiser($appraiserRow);
            
            // 2.1 Insert Appraiser To Enterprise  
            $dataApEnt = isset($data['allowAppraiser'])?$data['allowAppraiser']:null;
            
            
            if ($dataApEnt) {
                for ($i = 0; $i < count($dataApEnt['enterprise_id']); $i++) {
                    $dataEnt['enterprise_id'] = $dataApEnt['enterprise_id'][$i];
                    $insertApEnt = $this->createAppraiserToEnterprise($appraiserRow,$dataEnt);
                    if (!$insertApEnt['status']) {
                        throw new Vtx_UserException($insertApEnt['messageError']);
                    }
                }
            }

            // end transaction externo
            Zend_Registry::get('db')->commit();
            
            return array(
                'status' => true
            );
            
        } catch (Vtx_UserException $e) {
            Zend_Registry::get('db')->rollBack();
            return array(
                'status' => false, 
                'messageError' => $e->getMessage()
            );
        } catch (Exception $e) {
            Zend_Registry::get('db')->rollBack();
            throw new Exception($e);
        }
    }

    protected function _filterInputAppraiserToEnterprise($params)
    {
        $input = new Zend_Filter_Input(
            array( //filters
                'enterprise_id' => array(
                ),
            ),
            array( //validates
                'enterprise_id' => array('NotEmpty',
                    'messages' => array('Empresa não informada.')
                ),
            ),
            $params,
            array('presence' => 'required')
        );

        if ($input->hasInvalid() || $input->hasMissing()) {
            throw new Vtx_UserException(
                Model_ErrorMessage::getFirstMessage($input->getMessages())
            );
        }

        return $input;
    }

    public function deleteAppraiserEnterpriseByAppraiser($appraiserRow)
    {   
        /* Deleta todos os resultados anuais da questão */
        $whereDelete = array('UserId = ?' => $appraiserRow->getId());
        $this->DbAppraiser->delete($whereDelete);

        return array(
            'status' => true
        );

    }

    public function getAppraiserEnterpriseById($Id)
    {
        return $this->DbAppraiser->fetchRow(array('Id = ?' => $Id));
    }
    

    public function getAll($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->DbAppraiser->fetchAll($where, $order, $count, $offset);
    }
    
    /**
     * Verifica se o avalidor tem permissao de avaliar a emprea
     * @param type $idEnterprise
     * @param type $idUserLogged
     * @param type $idProgram
     */
    public function isPermit($idEnterprise, $idUserAppraiser, $idProgram, $etapa = 'estadual')
    {
        $tiposAvaliador = ($etapa == 'estadual')? array(1, 2, 3) : array(4, 5, 6);
        $where = array(
            'EnterpriseId = ?' => $idEnterprise,
            'UserId = ?' => $idUserAppraiser, 
            'ProgramaId = ?' => $idProgram,
            'AppraiserTypeId in (?)' => $tiposAvaliador
        );
        return $this->DbAppraiser->fetchRow($where);
    }
    
    /**
     * Salva uma avalição do relato de uma candidata
     * @param type $evaluationRow
     * @param type $linhas1
     * @param type $linhas2
     * @param type $answers
     * @param type $finalizar
     * @return type
     * @throws Exception
     */
    public function saveEvaluation(
        $questions, $evaluationRow, $linhas1, $linhas2, $answers = array(), $conclusao = '', $finalizar = false
    ) {
        $tbApeEvaluation = DbTable_ApeEvaluation::getInstance();
        $appraiserEnterpriseId = $evaluationRow->getId();
        $pontosFinal = 0;
        $finalizacaoSucesso = true;
        $questionsError = array();

        Zend_Registry::get('db')->beginTransaction();
        try {
            $evaluationRow->setStatus('I')->save();

            $tbApeEvaluation->delete(array(
                'AppraiserEnterpriseId = ?' => $appraiserEnterpriseId
            ));
            
            foreach ($questions as $question) {
                //$question['peso']
               
                $questionId = $question['Id'];
                if (!isset($answers[$questionId]) and !$linhas1[$questionId] and !$linhas2[$questionId]) {
                    $finalizacaoSucesso = false;
                    $questionsError[$question->getBloco()][$question->getCriterio()][$question->getQuestaoLetra()] = array();
                    continue;
                }
                if (!isset($answers[$questionId])
                    or (
                        $answers[$questionId] != 'D' and (!$linhas1[$questionId] or !$linhas2[$questionId] or $linhas1[$questionId] > $linhas2[$questionId])
                    )
                ) {
                    $finalizacaoSucesso = false;
                    $questionsError[$question->getBloco()][$question->getCriterio()][$question->getQuestaoLetra()] = array();
                }
                $appraiserEntRow = $tbApeEvaluation->createRow()
                    ->setAppraiserEnterpriseId($appraiserEnterpriseId)
                    ->setAvaliacaoPerguntaId($questionId)
                    ->setResposta(isset($answers[$questionId])? $answers[$questionId] : null)
                    ->setLinha1($linhas1[$questionId])
                    ->setLinha2($linhas2[$questionId]);
                $appraiserEntRow->save();
                
                if (isset($answers[$questionId]) and $answers[$questionId] == 'A') {
                    $pontosFinal += ($question['Peso'] * 0.5); //50%
                } elseif (isset($answers[$questionId]) and $answers[$questionId] == 'S') {
                    $pontosFinal += ($question['Peso'] * 1); //100%
                }                    
                
            }
            
            
            if ($conclusao) {
                $evaluationRow->setConclusao($conclusao)
                    ->setConclusaoDate(New Zend_Db_Expr('NOW()'))
                    ->save();
            }

            if ($finalizar and $finalizacaoSucesso and $conclusao) {
                $evaluationRow->setStatus('C')->setPontos($pontosFinal)->save();
            }
            
            Zend_Registry::get('db')->commit();
            
            return array(
                'status' => true,
                'finalizacaoSucesso' => $finalizacaoSucesso,
                'questionsError' => $questionsError
            );
        } catch (Vtx_UserException $e) {
            Zend_Registry::get('db')->rollBack();
            return array(
                'status' => false, 
                'messageError' => $e->getMessage(),
                'questionsError' => $questionsError
            );
        } catch (Exception $e) {
            Zend_Registry::get('db')->rollBack();
            throw new Exception($e);
        }
    }
    
    /**
     * Verifica se o verificador tem permissao de avaliar a emprea
     * @param type $idEnterprise
     * @param type $idUserLogged
     * @param type $idProgram
     */
    public function isCheckerPermit($idEnterprise, $idUserAppraiser, $idProgram, $nacional=1)
    { 
        $where = array(
            'EnterpriseId = ?' => $idEnterprise,
            'UserId = ?' => $idUserAppraiser, 
            'ProgramaId = ?' => $idProgram, 
        	'CheckerTypeId = ?' => $nacional
        );
        //var_dump ($this->DbChecker->fetchRow($where));die;
        return $this->DbChecker->fetchRow($where);
    }
    
    /**
     * Pega a avaliação da empresa feito pelo verificador
     * @param type $idEnterprise
     * @param type $idProgram
     */
    public function getCheckerEvaluation($idEnterprise, $idProgram, $nacional = null)
    {
    	if ($nacional == 2)
    	{
	        $where = array(
	            'EnterpriseId = ?' => $idEnterprise,
	            'ProgramaId = ?' => $idProgram,
	        	'CheckerTypeId =?' => $nacional
	        );
    	} else {
    		$where = array(
    				'EnterpriseId = ?' => $idEnterprise,
    				'ProgramaId = ?' => $idProgram
    		);
    	}
        return $this->DbChecker->fetchRow($where);
    }
    
    /**
     * Salva uma avalição do verificador
     * @param type $evaluationRow
     * @param type $linhas1
     * @param type $linhas2
     * @param type $answers
     * @param type $finalizar
     * @return type
     * @throws Exception
     */
    public function saveCheckerEvaluation(
        $commentQuestions, $evaluationQuestions, $evaluationRow, $comments = array(), $answers = array(), $conclusao = '', $finalizar = false
    ) {
        $tbCheckerEvaluation = DbTable_CheckerEvaluation::getInstance();
        $checkerEnterpriseId = $evaluationRow->getId();
        $finalizacaoSucesso = true;
        $criteriosError = $evaluationQuestionsError = array();

        Zend_Registry::get('db')->beginTransaction();
        try {           
            
            $evaluationRow->setStatus('I')->save();            

            $tbCheckerEvaluation->delete(array(
                'CheckerEnterpriseId = ?' => $checkerEnterpriseId
            ));
                       
            $qtdePontosForte = 0;
            foreach ($evaluationQuestions as $question) {
                $questionId = $question['Id'];
                if (!isset($answers[$questionId])) {
                    $finalizacaoSucesso = false;
                    $evaluationQuestionsError[$questionId] = array();
                    continue;
                }
                $resposta = isset($answers[$questionId])? $answers[$questionId] : null;
                if ($resposta == 'F') {                    
                                     
                    $qtdePontosForte++;
                }
               
                $checkerEntRow = $tbCheckerEvaluation->createRow()
                    ->setCheckerEnterpriseId($checkerEnterpriseId)
                    ->setQuestionCheckerId($questionId)
                    ->setResposta($resposta)
                    ->setCheckerEvaluationTypeId(2); 
               
                $checkerEntRow->save();
            }           
            
            if ($conclusao) {
                $evaluationRow->setConclusao($conclusao)
                ->setConclusaoDate(New Zend_Db_Expr('NOW()'))
                ->setQtdePontosFortes($qtdePontosForte)
                ->save();
            }
            
            $criterioAnterior = '';
            foreach ($commentQuestions as $question) {
                $criterio = "{$question->getBloco()}{$question->getCriterio()}";
                if ($criterioAnterior==$criterio) {
                    continue;
                }
                $criterioAnterior = $criterio;
                
                if (!isset($comments[$criterio]) or trim($comments[$criterio]) == '') {
                    $finalizacaoSucesso = false;
                    $criteriosError[$question->getBloco()][$question->getCriterio()] = array();
                    continue;
                }
                
                                
                $checkerEntRow = $tbCheckerEvaluation->createRow()
                    ->setCheckerEnterpriseId($checkerEnterpriseId)
                    ->setCriterionNumber($criterio)
                    ->setComment($comments[$criterio])
                    ->setCheckerEvaluationTypeId(1);
                $checkerEntRow->save();
            }

            if ($finalizar and $finalizacaoSucesso and $conclusao and $qtdePontosForte) {
                
                $evaluationRow
                    ->setQtdePontosFortes($qtdePontosForte)
                    ->setStatus('C')->save();
            }

          Zend_Registry::get('db')->commit();

            return array(
                'status' => true,
                'finalizacaoSucesso' => $finalizacaoSucesso,
                'criteriosError' => $criteriosError,
                'evaluationQuestionsError' => $evaluationQuestionsError
            );
        } catch (Exception $e) {
            Zend_Registry::get('db')->rollBack();
            throw new Exception($e);
        }
    }
    
    
    function getEnterpriseScoreAppraisersData($enterpriseId, $competitionId = null, $fase = null)
    {
        if (!$competitionId) {
            $competitionId = Zend_Registry::get('configDb')->competitionId;
        }
        return $this->DbEnterprise->getEnterpriseScoreAppraisersData($enterpriseId, $competitionId, $fase);
    }
    function getEnterpriseScoreAppraiserAnwserAvaliatorData($enterpriseId, $competitionId = null)
    {
        if (!$competitionId) {
            $competitionId = Zend_Registry::get('configDb')->competitionId;
        }
        return $this->DbApeEvaluation->getEnterpriseScoreAppraiserAnwserAvaliatorData($enterpriseId, $competitionId);
    }
    
    function getCheckerEvaluations($enterpriseId, $competitionId = null)
    {
        if (!$competitionId) {
            $competitionId = Zend_Registry::get('configDb')->competitionId;
        }
        return $this->DbCheckerEvaluation->getCheckerEvaluations($enterpriseId, $competitionId);
    }
    
 
    public function saveApeEvaluationVerificador(
        $questions, $evaluationRow, $answers = array(), $conclusao = '', $finalizar = false
    ) {                
 
        $tbApeEvaluationVerificador = DbTable_ApeEvaluationVerificador::getInstance();
        $appraiserEnterpriseId = $evaluationRow->getId();
		$userId = $evaluationRow->getUserId();
        $pontosFinal = 0;
        $finalizacaoSucesso = true;
        $questionsError = array();

        Zend_Registry::get('db')->beginTransaction();
        try {
        	
            $evaluationRow->setStatus('I');
            $evaluationRow->save();   
            
            $tbApeEvaluationVerificador->delete(array(
                'AppraiserEnterpriseId = ?' => $appraiserEnterpriseId
            ));
            
            foreach ($questions as $question) {
                $questionId = $question['Id'];              
                
                if (!isset($questionId)) {
                    $finalizacaoSucesso = false;
                    $questionsError[$question['Bloco']][$question['Criterio']][$question['QuestaoLetra']] = array();
                    continue;
                }
               
                if (!isset($questionId)
                    or ($questionId != 1)) 
                {
                    $finalizacaoSucesso = false;
                    $questionsError[$question['Bloco']][$question['Criterio']][$question['QuestaoLetra']] = array();
                }               

                if (isset($answers[$questionId]) and $answers[$questionId] == 2) {
                    $pontosFinal += ($question['Peso'] * 0.5); //50%
                } elseif (isset($answers[$questionId]) and $answers[$questionId] == 3) {
                    $pontosFinal += ($question['Peso'] * 1); //100%                   
                }               

                $respostas = isset($answers[$questionId])? $answers[$questionId] : null;              

                $appraiserEntRow = $tbApeEvaluationVerificador->createRow()
                ->setAppraiserEnterpriseId($appraiserEnterpriseId)
                ->setAvaliacaoPerguntaId($questionId)
                ->setResposta($respostas)
                ->setDate(New Zend_Db_Expr('NOW()'))                
                ->setPontosFinal($pontosFinal)
				->setUserId($userId);

                $appraiserEntRow->save();
            }           
            
            if ($conclusao) {
                $evaluationRow
                ->setConclusao($conclusao)
                ->setConclusaoDate(New Zend_Db_Expr('NOW()'))                
                ->save();
            }
    
            if ($finalizar and $finalizacaoSucesso and $conclusao) {
                $evaluationRow->setStatus('C')->setPontos($pontosFinal)->save();
            }
    
            Zend_Registry::get('db')->commit();
   
            return array(
                'status' => true,
                'finalizacaoSucesso' => $finalizacaoSucesso,
                'questionsError' => $questionsError
            );
        } catch (Vtx_UserException $e) {
            Zend_Registry::get('db')->rollBack();
            return array(
                'status' => false,
                'messageError' => $e->getMessage(),
                'questionsError' => $questionsError
            );
        } catch (Exception $e) {
            Zend_Registry::get('db')->rollBack();
            throw new Exception($e);
        }
    }
    
    function getEnterpriseScoreAppraiserAnwserVerificadorData($enterpriseId,$userId, $competitionId = null, $nacional = null)
    {
        if (!$competitionId) {
            $competitionId = Zend_Registry::get('configDb')->competitionId;
        }
        
        return $this->DbApeEvaluationVerificador->getEnterpriseScoreAppraiserAnwserVerificadorData($enterpriseId,$userId,$competitionId, $nacional);
    }    
	
	    public function saveApeEvaluationVerificadorComment(
        $commentQuestions, $evaluationQuestions, $comments = array(), $appraiserEnterpriseId, $userId
    ) {              

       //$evaluationRow
	$conclusao = '';
	$finalizar = false;  
 
        $tbApeEvaluationVerificadorComment = DbTable_ApeEvaluationVerificadorComment::getInstance();
        $appraiserEnterpriseId = $appraiserEnterpriseId;
		$userId = $userId;
        $finalizacaoSucesso = true;
        $questionsError = array();
		   
        Zend_Registry::get('db')->beginTransaction();
        try {
            $tbApeEvaluationVerificadorComment->delete(array(
                'AppraiserEnterpriseId = ?' => $appraiserEnterpriseId
            ));

            foreach ($commentQuestions as $question) {
                $questionId = $question['Id'];              
                
                if (!isset($questionId)) {
                    $finalizacaoSucesso = false;
                    $questionsError[$question['Bloco']][$question['Criterio']] = array();
                    continue;
                }
               
                if (!isset($questionId) or ($questionId != 1)) 
                {
                    $finalizacaoSucesso = false;
                    $questionsError[$question['Bloco']][$question['Criterio']] = array();
                }               
             }

	    $criterioAnterior = '';
            foreach ($commentQuestions as $question) {

               // $criterio = "{$question->getBloco()}{$question->getCriterio()}";
              //  if ($criterioAnterior==$criterio) {
              //      continue;
               // }

				$criterio = $question->getBloco().$question->getCriterio();

                if (!isset($comments[$criterio]) or trim($comments[$criterio]) == '') {
                    $finalizacaoSucesso = false;
                    $criteriosError[$question->getBloco()][$question->getCriterio()] = array();
                    continue;
                }    
				
				 if ($criterioAnterior==$criterio) {
                   		continue;
                  }
				
				$appraiserEntRow = $tbApeEvaluationVerificadorComment->createRow()
				->setComment($comments[$criterio])
				->setCriterioNumber($criterio)
				->setAppraiserEnterpriseId($appraiserEnterpriseId)
				->setUserId($userId);			
				
				$appraiserEntRow->save();
				$criterioAnterior = $criterio;
			                             
             }   			

            if ($conclusao) {
                $evaluationRow
                ->setConclusao($conclusao)
                ->setConclusaoDate(New Zend_Db_Expr('NOW()'))                
                ->save();
            }
    
            Zend_Registry::get('db')->commit();
    
            return array(
                'status' => true,
                'finalizacaoSucesso' => $finalizacaoSucesso,
                'questionsError' => $questionsError
            );

            } 
	catch (Vtx_UserException $e) {
            Zend_Registry::get('db')->rollBack();
            return array(
                'status' => false,
                'messageError' => $e->getMessage(),
                'questionsError' => $questionsError
            );
        }
	catch (Exception $e) {
            Zend_Registry::get('db')->rollBack();
            throw new Exception($e);
        }
    }
	
	    function getApeEvaluationVerificadorComment($enterpriseId,$userId)
    {
        return $this->DbApeEvaluationVerificadorComment->getApeEvaluationVerificadorCommentFind($enterpriseId,$userId);
    }    
}
