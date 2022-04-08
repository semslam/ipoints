<?php

interface QueryModifier 
{   
    public function getParam($key);
    public function generatePeriod($periodField = '');
    public function generateOrderBy($default='', array $columns=[]);
    public function generateLimit();
    public function generateSearch($columns = []);
    public function generateConditions(array $options);
    public function compileQuery($query, array $criteria=[], $periodCol='', $orderByDef='');
    public function getResult(array $params=[]);
    public function getQueryString();
}
