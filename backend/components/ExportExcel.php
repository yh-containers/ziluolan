<?php
namespace backend\components;

class ExportExcel{
    public static function handleData($data,$filename='excel')
    {
        $objPHPExcel = new \PHPExcel();

        //设置文件的一些属性，在xls文件——>属性——>详细信息里可以看到这些值，xml表格里是没有这些值的
        $objPHPExcel
            ->getProperties()  //获得文件属性对象，给下文提供设置资源
            ->setCreator( "MaartenBalliauw")             //设置文件的创建者
            ->setLastModifiedBy( "MaartenBalliauw")       //设置最后修改者
            ->setTitle( "Office2007 XLSX Test Document" )    //设置标题
            ->setSubject( "Office2007 XLSX Test Document" )  //设置主题
            ->setDescription( "Test document for Office2007 XLSX, generated using PHP classes.") //设置备注
            ->setKeywords( "office 2007 openxmlphp")        //设置标记
            ->setCategory( "Test resultfile");                //设置类别
        // 位置aaa *为下文代码位置提供锚
        //给表格添加数据
        foreach ($data as $key=>$vo){
            for($i=0;$i<count($vo);$i++){
                $objPHPExcel->getActiveSheet()
                    ->setCellValueByColumnAndRow($i,$key+1,$vo[$i])
                ;

            }
        }
        //我们将要做的是
        //1,直接生成一个文件
        $objWriter =\PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('myexchel.xlsx');
        header('Content-Type:application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.$filename.'.xls"');
        header('Cache-Control:max-age=0');
        $objWriter =\PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
}