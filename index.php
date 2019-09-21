<?php
    
    $keyFile = "lock.lock";

    function Lock()
    {
        global $keyFile;
        
        WaitLock();
        touch($keyFile);
    }
    
    function Unlock()
    {
        global $keyFile;
        
        unlink($keyFile);
    }
    
    function WaitLock()
    {
        global $keyFile;
        
        $i = 0;
        while (file_exists($keyFile))
        {
            sleep(0.5);
            $i++;
        }
        
        if ($i == 15)
            unlink($keyFile);
    }



    $ChatApi = 'ChatApi.txt';
    $MachineMessageApi = 'MachineMessageApi.txt';

    if (isset($_REQUEST['ChatApi']))
    {
        $text = $_REQUEST['ChatApi'];
        
        Lock();
        file_put_contents($ChatApi, $text . "\n", FILE_APPEND);
        Unlock();
    }
    elseif (isset($_REQUEST['MachineMessageApi']))
    {
        $text = $_REQUEST['MachineMessageApi'];
        
        Lock();
        file_put_contents($MachineMessageApi, $text . "\n", FILE_APPEND);
        Unlock();
    }
    elseif (isset($_REQUEST['Get']))
    {
        $text = $_REQUEST['Get'];
        
        if ($text == "ChatApi")
        {
            Lock();
            $current = file_get_contents($ChatApi);
            file_put_contents($ChatApi, '');
            
            $list = explode("\n", $current);
            $filtered = [];
            
            foreach ($list as $item)
                if (strlen($item) > 0) 
                    $filtered[] = $item;
            
            echo json_encode($filtered);
            
            Unlock();
        }
        elseif ($text == "MachineMessageApi")
        {
            Lock();
            $current = file_get_contents($MachineMessageApi);
            file_put_contents($MachineMessageApi, '');
            
            $list = explode("\n", $current);
            $filtered = [];
            
            foreach ($list as $item)
                if (strlen($item) > 0) 
                    $filtered[] = $item;
            
            echo json_encode($filtered);
            
            Unlock();
        }
        else
        {
            echo "Unknown Get value";
        }
    }
    else
    {
        echo "GET ?ChatApi=text and ?MachineMessageApi=text and ?Get=ChatApi and ?Get=MachineMessageApi";
    }
    
    
?>