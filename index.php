<html lang="zh-CN">

<head>
    <!-- META -->
    <meta charset="UTF-8" />
    <meta name="description" content="SKYTown Server文件仓库" />
    <meta name="keywords" content="File,Repository,SKYTown,JasonZYT" />
    <meta name="author" content="JasonZYT&PluginKers" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- TITLE -->
    <title>SKYTown - File Repository</title>
    <!-- ICON -->
    <link href="assets/img/icon.ico" rel="shortcut icon">
    <link rel="bookmark" href="assets/img/icon.ico" />
    <!-- LINK-CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/font.css">
    <link rel="stylesheet" href="assets/css/style.min.css">
</head>

<body style="overflow: visible;background:#f6f6f6;">
    <?php include "assets/svg/icon.svg" ?>
    <nav id="navbar" style="display:block;">
        <div class="row">
            <div class="container">
                <div class="logo unit">
                    <!-- <img src="" alt="logo"> -->
                    <span>SKYTown</span>
                </div>
                <ul class="nav-menu">
                    <?php
			        function convertUrlQuery($query)
			        {
				        $queryParts = explode('&', $query);
				        $params = array();
				        foreach ($queryParts as $param) {
					        $item = explode('=', $param);
					        $params[$item[0]] = $item[1];
				        }
				    return $params;
			        }
			        $host = $_SERVER['HTTP_HOST'];
			        $lic = $_SERVER['REQUEST_SCHEME'];
			        $port = $_SERVER['SERVER_PORT'];
			        $url = $lic . "://" . $host;
			        $query = $_SERVER['QUERY_STRING'];
			        $parUrl = convertUrlQuery($query);
                    $parameter3 = urldecode($parUrl["dir"]);
                    $parameter2 = str_replace("\\","/",$parameter3);
                    $parameter1 = str_replace("//","/",$parameter2);
                    $parLen1 = mb_strlen($parameter1);
			        if (mb_substr($parameter1,$parLen-1,1)=="/") {
                        $parameter = mb_substr($parameter1,0,$parLen-1);
                    }
                    else {
                        $parameter = $parameter1;
                    }
                    $parLen = mb_strlen($parameter);
                    $parLastSlashPos = mb_strrpos($parameter,"/");
                    $currentDirName = mb_substr($parameter,$parLastSlashPos+1,$parLen-1);
                    if ($parLastSlashPos==false) {
                        $currentDirName = $parameter;
                    }
                    if (file_exists("./Filedir/" . $parameter)==false){
                        echo '<script language="javascript"> window.location.href="error/FILE404.php?dir=' . $parameter . '"</script>';
                    }
                    if ($parLen==0) {
				        echo '<li><a href="#">首页</a></li>';
                    }
			        else {
                        echo '<li><a href="' . $url . '/index.php">首页</a><svg><use xlink:href="#AngleBracket-R" /></svg></li>';
				        for ($i=0;$i<=$parLen-1;$i++) {
				    	    if (mb_substr($parameter,$i,1)=="/") {
                                $dir = mb_substr($parameter,0,$i);
                                if (mb_substr($dir,0,1)!="/") {
                                    $dirSlash = "/" . $dir;
                                }
                                else {
                                    $dirSlash = $dir;
                                }
                                $lastSlashPos = mb_strrpos($dirSlash,"/");
                                $dirLen = mb_strlen($dirSlash);
                                $dirName = mb_substr($dirSlash,$lastSlashPos+1,$dirLen-1);
				    		    echo '<li><a href="' . $url . '/index.php?dir=' . $dir . '">' . $dirName . '</a><svg><use xlink:href="#AngleBracket-R"></use></svg></li>';
                            }
                        }
				    	echo '<li><a style="color:#707070;">' . $currentDirName . '</a></li>';
			        }
			        ?>
                </ul>
            </div>
        </div>
    </nav>
    <section id="list" class="services-section spad">
        <div class="container">
            <div id="dir-list-header">
                <div class="row">
                    <div class="file-name col-md-7 col-sm-6 col-xs-9">文件</div>
                    <div class="file-size col-md-2 col-sm-2 col-xs-3 text-right">大小</div>
                    <div class="last-edit-time col-md-3 col-sm-4 hidden-xs text-right">上次修改时间</div>
                </div>
            </div>
            <ul id="dir-list" class="nav nav-pills nav-stacked">
                <?php
                function getFileSizeStr($fileSize) {
                    if ($fileSize >= 1024 && $fileSize < 1048576)
                    {
                        return $fileSize / 1024 . "KB";
                    }
                    else if ($fileSize >= 1048576 && $fileSize < 1073741824)
                    {
                        return $fileSize / 1048576 . "MB";
                    }
                    else if ($fileSize >=1073741824  && $fileSize < 1099511627776)
                    {  
                        return $fileSize / 1073741824 . "GB";
                    }
                    else
                    {
                        return $fileSize . "Byte";
                    }
                }
                function ergodicDir($inDir){
                    $dir = "./Filedir/" . $inDir;
                    $files = scandir($dir); 
                    foreach ($files as $file)
                    {
                        if ($file=='.'||$file=='..') continue;
                        $lastEditTimeStamp = filemtime ($dir . '/' . $file);
                        $lastEditTime = date("Y-m-d H:i:s",$lastEditTimeStamp);
                        if (is_dir($dir . '/' . $file)) {
                            if ($inDir == "")
                            {
                                echo <<<EOF
                                    <li data-name="$file" data-href="?dir=$file">
                                        <a href="?dir=$file" class="clearfix" data-name="$file">
                                            <div class="row">
                                                <span class="file-name col-md-7 col-sm-6 col-xs-9">
                                                    <svg><use xlink:href="#Folder"/></svg>
                                                    $file
                                                </span>
                                                <span class="file-size col-md-2 col-sm-2 col-xs-3 text-right">
                                                    -
                                                </span>
                                                <span class="last-edit-time col-md-3 col-sm-4 hidden-xs text-right">
                                                    $lastEditTime
                                                </span>
                                            </div>
                                        </a>
                                    </li>
                                EOF;
                            }
                            else {
                                echo <<<EOF
                                    <li data-name="$file" data-href="?dir=$inDir/$file">
                                        <a href="?dir=$inDir/$file" class="clearfix" data-name="$file">
                                            <div class="row">
                                                <span class="file-name col-md-7 col-sm-6 col-xs-9">
                                                    <svg><use xlink:href="#Folder"/></svg>
                                                    $file
                                                </span>
                                                <span class="file-size col-md-2 col-sm-2 col-xs-3 text-right">
                                                    -
                                                </span>
                                                <span class="last-edit-time col-md-3 col-sm-4 hidden-xs text-right">
                                                    $lastEditTime
                                                </span>
                                            </div>
                                        </a>
                                    </li>
                                EOF;
                            }
                        }
                        else {
                            $fileSize = filesize($dir . "/" . $file);
                            $FS = getFileSizeStr($fileSize);
                            $FE = mb_substr(mb_strrchr($file, '.'), 1);
                            if (
                            $FE=="c"||$FE=="i"||$FE=="s"||$FE=="o"||$FE=="out"||$FE=="cxx"||$FE=="cc"||$FE=="c++"||$FE=="C"||$FE=="cpp"||
                            $FE=="inl"||$FE=="hpp"||$FE=="hxx"||$FE=="h++"||$FE=="h"||$FE=="cs"||$FE=="aspx"||$FE=="resx"||$FE=="json"||$FE=="md"||
                            $FE=="py"||$FE=="pyo"||$FE=="pyw"||$FE=="pyc"||$FE=="pyd"||$FE=="php"||$FE=="phps"||$FE=="lua"||$FE=="go"||$FE=="sln"||
                            $FE=="ttf"||$FE=="otf"||$FE=="woff"||$FE=="woff2"||$FE=="eot"||$FE=="apk"||$FE=="xapk"||$FE=="css"||$FE=="less"||$FE=="js"||
                            $FE=="exe"||$FE=="log"||$FE=="doc"||$FE=="docx"||$FE=="docm"||$FE=="dot"||$FE=="dotx"||$FE=="dotm"||$FE=="jpg"||$FE=="png"||
                            $FE=="jpeg"||$FE=="bmp"||$FE=="gif"||$FE=="tif"||$FE=="pcx"||$FE=="tga"||$FE=="exif"||$FE=="fpx"||$FE=="ai"||$FE=="raw"||
                            $FE=="webp"||$FE=="pdf"||$FE=="ppt"||$FE=="pptx"||$FE=="pptm"||$FE=="potx"||$FE=="potm"||$FE=="pot"||$FE=="ppsx"||$FE=="ppsm"||
                            $FE=="ppa"||$FE=="ppam"||$FE=="zip"||$FE=="xml"||$FE=="ini"||$FE=="cfg"||$FE=="config"||$FE=="conf"||$FE=="propreties"||$FE=="ipa"||
                            $FE=="plist"||$FE=="applescript"||$FE=="ps1"||$FE=="bat"||$FE=="sh"||$FE=="bash"||$FE=="html"||$FE=="htm"||$FE=="dll"||$FE=="lib"||
                            $FE=="txt"||$FE="gitignore"
                            ) {
                                echo <<<EOF
                                    <li data-name="$file" data-href="?dir=$inDir/$file">
                                        <a href="$dir/$file" class="clearfix" data-name="$file">
                                            <div class="row">
                                                <span class="file-name col-md-7 col-sm-6 col-xs-9">
                                                    <svg><use xlink:href="#.$FE"/></svg>
                                                    $file
                                                </span>
                                                <span class="file-size col-md-2 col-sm-2 col-xs-3 text-right">
                                                    $FS
                                                </span>
                                                <span class="last-edit-time col-md-3 col-sm-4 hidden-xs text-right">
                                                    $lastEditTime
                                                </span>
                                            </div>
                                        </a>
                                    </li>
                                EOF;
                            }
                            elseif ($FE == "url") {
                                $urlFile = file($dir . "/" . $file);
                                $url = $urlFile[1];
                                echo <<<EOF
                                    <li data-name="$file" data-href="?dir=$inDir/$file">
                                        <a href="$url" class="clearfix" data-name="$file">
                                            <div class="row">
                                                <span class="file-name col-md-7 col-sm-6 col-xs-9">
                                                    <svg><use xlink:href="#Url"/></svg>
                                                    $file
                                                </span>
                                                <span class="file-size col-md-2 col-sm-2 col-xs-3 text-right">
                                                    -
                                                </span>
                                                <span class="last-edit-time col-md-3 col-sm-4 hidden-xs text-right">
                                                    $lastEditTime
                                                </span>
                                            </div>
                                        </a>
                                    </li>
                                EOF;
                            }
                            else {
                                echo <<<EOF
                                    <li data-name="$file" data-href="?dir=$inDir/$file">
                                        <a href="$dir/$file" class="clearfix" data-name="$file">
                                            <div class="row">
                                                <span class="file-name col-md-7 col-sm-6 col-xs-9">
                                                    <svg><use xlink:href="#Unknown"/></svg>
                                                    $file
                                                </span>
                                                <span class="file-size col-md-2 col-sm-2 col-xs-3 text-right">
                                                    $FS
                                                </span>
                                                <span class="last-edit-time col-md-3 col-sm-4 hidden-xs text-right">
                                                    $lastEditTime
                                                </span>
                                            </div>
                                        </a>
                                    </li>
                                EOF;
                            }
                        }
                    }
                }
                ergodicDir($parameter);
                ?>
            </ul>
        </div>
    </section>
    <footer>
        <p>Copyright ©2020 Ver1.0.2</p>
        <p>SKYTown Server All Rights Reserved.</p>
        <p>Power By PluginKers & JasonZYT</p>
        <p id="hitokoto"></p>
        <script src="https://v1.hitokoto.cn/?encode=js&amp;select=%23hitokoto" defer=""></script>
    </footer>

    <script type="text/javascript" src="assets/js/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="assets/js/jquery.nav.js"></script>

</body>

</html>