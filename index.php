<?php
header('Content-type: application/json');
require('./Jenkins.php');
require('./Jenkins/Build.php');
require('./Jenkins/Computer.php');
require('./Jenkins/Executor.php');
require('./Jenkins/Factory.php');
require('./Jenkins/Job.php');
require('./Jenkins/JobQueue.php');
require('./Jenkins/Queue.php');
require('./Jenkins/TestReport.php');
require('./Jenkins/View.php');

/// 用户名配置
$username= 'xiongcaichang';

///token 配置
$token='c1f2a33236ebcece5cbb1c1380d7620e';

//点击用户名  设置   api token获取   需要 jekins  关闭防止外站模拟请求
$jenkins = new \JenkinsKhan\Jenkins('http://'.$username.':'.$token.'@127.0.0.1:8080');


// jenken项目名称
$jobName = $_GET['jobname'];
//操作名称
$actionName = $_GET['action'];

//默认操作状态  Scode=1   Success
$info['Scode'] = 1;

if($jobName){

    $job = $jenkins->getJob($jobName);

    //开始创建
    if($actionName == 'build'){
        
        if(!$jenkins->launchJob($jobName)){
             $info['Scode'] = 0;
        }
        //获取最后一次创建状态
    }else if($actionName == 'getlastbuild'){

        $builds = $job->getBuilds();
        if($builds){
          $lastBuild = $builds[0];
           $info['number'] = $lastBuild->getNumber();
           $info['result'] = $lastBuild->getResult();
        }

        //获取该项目所有构建状态
    }else if($actionName == 'getbuilds'){
         $buildsTmp = array();
        foreach ($job->getBuilds() as $build) {
            $buildDic['number'] = $build->getNumber();
            $buildDic['result'] = $build->getResult();
            array_push($buildsTmp,$buildDic);
        }
        $info['builds'] = $buildsTmp;
    }
}else{
       $info['Scode'] = 0;
}

echo json_encode($info);