<h1 class="title tquiz">Questionário de Autoavaliação</h1>
 <h4 class="subtitle tquiz">

        <!--?php if (isset($this->enterpriseRow) and isset($this->isViewAdmin) and $this->isViewAdmin): ?-->
            <h4 class="subtitle tquiz">
            <?php echo $this->escape($this->enterpriseRow->getSocialName()); ?>
            (<?php echo $this->escape($this->enterpriseRow->getFantasyName()); ?>)</h4>
        <!--?php endif; ?-->

<p class="description-quizz"><?php echo "Para cada questão <strong>clique na resposta</strong> e depois no <strong>botão 'Salvar'</strong>."; ?></p>

<div class="innet-content">
  <div class="quizz">
    <ul>
      <?php
      $cont = 1;
      foreach ($this->blockQuestions as $questionId => $question): 
        $questionSummary = $question['QuestionSummary'];
        $questionValue = $question['QuestionValue'];
        $questionType = $question['QuestionTypeId'];
        $questionTypeCssName = ( Model_QuestionType::AGREEDISAGREE_ID ? 'questionTypeSubmitButton' : 'questionTypeSubmitRadioChange');
      ?>
      <li id="marker-<?php echo $cont; ?>"><a href="#tab-<?php echo $cont; ?>"><span></span><?php echo $cont++; ?></a></li>
      <?php endforeach; ?>
    </ul>
      <div id="tabs-container" style="height: auto">
        <div id="tabs-list" style="height: auto">
     <?php
      $cont = 1;
      $pesos = array(
        1 => "dis-totalemente",
        2 => "discordo",
        3 => "nao-sei",
        4 => "concordo",
        5 => "con-plenament",
      );
      $disabled = $this->subscriptionPeriodIsClosed ? 'disabled' : '';
      foreach ($this->blockQuestions as $questionId => $question):
        $questionSummary = $question['QuestionSummary'];
        $questionValue = $question['QuestionValue'];
        $supportingText = $question['SupportingText'];
        $questionType = $question['QuestionTypeId'];
        $questionTypeCssName = ( Model_QuestionType::AGREEDISAGREE_ID ? 'questionTypeSubmitButton' : 'questionTypeSubmitRadioChange');
      ?>

      <div id="tab-<?php echo $cont; ?>" class="tab-item" style="height: auto">
        <form action="" class="formsubmitfull" data-question-id="<?php echo $questionId; ?>">
          <div class="label">
            <span class="number"><?php echo $cont++; ?>.</span>
            <span class="summary"><?php echo $questionSummary; ?></span>
            <a class="details">Saiba mais</a>
          </div>
          <div class="helper-questions">

              <p class="title-helper-questions" style="">Escolha uma Opção:</p>
              <?php echo $supportingText; ?>

          </div>

          <div class="details" style="display: none">
            <div class='fb-content'>
              <div class="header">
                <h1><?php echo $questionSummary ?></h1>
                <a class="close" href="#"></a>
              </div>
              <?php echo $questionValue ?>
            </div>
          </div>

         <div class="helper-questions">
         <p class="title-helper-questions">Resposta Verificador Estadual:</p>    
         </div>
         </br>
          <div class="answers" style="height: 146px">
            <?php
              $contPesos = 1; 
              foreach ($question['Alternatives'] as $alternativeId => $alternative):
            ?>
              <div id="<?php echo $pesos[$contPesos++]; ?>" class="answer">
                <span class="face"></span>
                <input type="radio" value="<?php echo $alternativeId; ?>" name="question[<?php echo $questionId; ?>]" 
                id="alternativeItem<?php echo $alternativeId; ?>" tabindex="-1" disabled/>
                <label class="label-inline" for="alternativeItem<?php echo $alternativeId; ?>">
                  <span class="radio-button"></span>
                  <?php echo $alternative['AlternativeValue']; ?>
                </label>                      
              </div>
            <?php endforeach; ?>
            <div class="fill">
              <div class="status-fill"></div>
            </div>            
          </div>
         <div class="helper-questions">
         <p class="title-helper-questions">Resposta Verificador Nacional:</p>    
         </div>
         </br>
        <div class="answers" style="height: 146px">
            <?php
              $contPesos = 1; 
              foreach ($question['Alternatives'] as $alternativeId => $alternative):
            ?>
              <div id="<?php echo $pesos[$contPesos++]; ?>" class="answer">                
                <span class="face"></span>
                
                <input type="radio" value="<?php echo $alternativeId; ?>" 
                name="questions[<?php echo $questionId; ?>]" 
                id="alternativeItemverificador<?php echo $alternativeId; ?>" 
                tabindex="-1" class="teste"
                />
                
                <label class="label-inline" for="alternativeItemverificador<?php echo $alternativeId; ?>">
                  <span class="radio-button"></span>
                  <?php echo $alternative['AlternativeValue']; ?>
                </label>                      
              </div>
            <?php endforeach; ?>
            <div class="fill">
              <div class="status-fill"></div>
            </div>            
          </div>          
          
                  <label class="label-control"> Comentário: </label>
          
          <textarea name="comentarioItemverificador<?php echo $questionId; ?>" style="width: 800px; height: 110px;"></textarea>  
              
          <div class="clearfix"></div>
          
          <?php if ($question['ShowEnterpriseFeedback']):?>
            <div class="complement">
            
                <button class="large btn-submit btSaveQuestionWithFeedback" type="submit" style="float: right; font-size: 16px;" tabindex="-1" <?php if ($this->periodoRespostas === false): ?>onClick="return false;"<?php endif; ?>><b>Salvar </b></button>
                <a class="large btn-submit help" style="float: right; font-size: 16px;"><b>Ajuda</b></a>
                <label for="FdbkQuestion<?php echo $questionId; ?>" style="font-size:12px;">Comentário Verificador Estadual:</label>
              <textarea readonly name="fdbkQuestion[<?php echo $questionId; ?>]" id="FdbkQuestion<?php echo $questionId; ?>"  tabindex="-1"></textarea>
              <div class="responseretu"></div> 
                           
            </div>
          <?php endif; ?>
        </form>
      </div>
      <?php endforeach; ?>  
      </div>    
      </div>    
  </div>
</div>

<div id='questionnaire-intro'>
    <div class='fb-content'>
        <div class='header'>
            <h1>Questionário de Autoavaliação da Gestão do Prêmio SEBRAE Mulher de Negócios - ciclo 2016</h1>
        </div>

        <p>Esta Autoavaliação da Gestão aborda os temas da gestão empresarial que são considerados na análise das candidatas concorrendo ao ciclo 2016 do Prêmio SEBRAE Mulher de Negócios (PSMN).  </p>
        <p>Nela são apresentados 16 tópicos da gestão empresarial aplicáveis a um pequeno negócio. Para responder a autoavaliação, escolha a opção de resposta que mais refletir a realidade em sua empresa.</p>
        <p>Seja o mais realista possível, pois uma devolutiva apontando os pontos fortes e as oportunidades de melhoria será gerada a partir de suas respostas. Além disso, suas respostas poderão ser validadas por um avaliador do Prêmio, caso sua empresa passe para a etapa de visitas, prevista para aquelas candidatas que se destacarem em termos de gestão e do relato enviado.</p> 
        <p><u>As candidatas visitadas que não evidenciarem o que tiverem respondido na autoavaliação da gestão, assim como descrito no relato, serão desclassificadas.</u>.</p>
        <div class='close'>
            <button class='large btn-submit'><b>Responder Questionário</b></button>
        </div>
    </div>
</div>
