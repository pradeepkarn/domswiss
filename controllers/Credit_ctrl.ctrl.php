<?php

class Credit_ctrl
{
    public function confirm_request($id,$dataObj)
    {
        return (new Model('credits'))->update($id,['status'=>'paid','info'=>$dataObj->info,'remark'=>'confirmed']);
    }
    public function cancel_request($id,$dataObj)
    {
        return (new Model('credits'))->update($id,['status'=>'cancelled','info'=>$dataObj->info,'remark'=>'cancelled']);
    }
}
