<?php
class TaggerOnWebPageInit extends TaggerPlugin
{
    public function run()
    {
        if ($this->modx->context->get('key') == 'mgr') {
            return;
        }

        $friendlyURL = $this->modx->getOption('friendly_urls', null, 0);
        if ($friendlyURL == 0) {
            return;
        }

        //$requestParamAlias = $this->modx->getOption('request_param_alias', null, 'q');
        //if (!isset($_REQUEST[$requestParamAlias])) return '';

        //$this->pieces = explode('/', trim($_REQUEST[$requestParamAlias], ' '));
        $this->pieces = array_filter(explode('/', trim($_SERVER['REQUEST_URI'], ' ')));
         
        $c = $this->modx->newQuery('TaggerGroup');
        $c->select($this->modx->getSelectColumns('TaggerGroup', '', '', array('alias')));
        $c->prepare();
        $c->stmt->execute();
        $this->groups = $c->stmt->fetchAll(PDO::FETCH_COLUMN, 0);
       
        $groups = array_flip($this->groups);
        $output = array();

        foreach ($this->pieces as $index => $piece) {
            if (!isset($groups[$piece])) {
              unset($this->pieces[$index]);
              continue;
            } else {
              unset($this->pieces[$index]);
              $output[$piece] = array_values($this->pieces);
              break;
            }
        }

        if (count($output) > 0) {
          $this->modx->toPlaceholders($output, 'tagger');
        }
        //$this->modx->log(modX::LOG_LEVEL_ERROR, print_r($output, true));
    }
}