<?php
    include_once 'functions.php';
    $conn = connectToDatabase('localhost', 'social_network', 'root', 'heslo');
?>
<html>
    <head>
        <title>Facebook</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="style.css"/>
        <script src="js/functions.js"></script>
    </head>
    <body>

        <?php
            include 'header.php'
        ?>
        </nav>
x;
        <div class="container">
            <div class="row">
                <div class="col-md-2">
                    <br/>
                    <div class="list-group">
                        <a href="#" class="list-group-item fc-list-group-item list-group-item-action">
                            <img src="images/profile-default.png" class="rounded-circle" style="width: 22px; margin-right: 4px"/>
                            Luciano Lopes
                        </a>
                        <a href="#" class="list-group-item fc-list-group-item list-group-item-action">
                            <div class="fc-icon fc-icon-feeds"><label style="width: 110px; font-size: 12px; margin-left: 28px;">News Feed</label></div>  
                        </a>
                        <a href="#" class="list-group-item fc-list-group-item list-group-item-action">
                            <div class="fc-icon fc-icon-messenger"><span>Messenger</span></div>                           
                        </a>
                        <a href="#" class="list-group-item fc-list-group-item list-group-item-action">
                            <div class="fc-icon fc-icon-watch"><span>Watch</span></div>                            
                        </a>
                        <a href="#" class="list-group-item fc-list-group-item list-group-item-action">
                            <div class="fc-icon fc-icon-marketplace"><span>Marketplace</span></div>  
                        </a>
                    </div>  
                    <label class="fc-label">Explore</label>
                    <div class="list-group">
                        <a href="#" class="list-group-item fc-list-group-item list-group-item-action">
                            <div class="fc-icon fc-icon-lembrancas"><span>Memories</span></div>  
                        </a>
                        <a href="#" class="list-group-item fc-list-group-item list-group-item-action">
                            <div class="fc-icon fc-icon-salvos"><span>Saved</span></div>  
                        </a>
                        <a href="#" class="list-group-item fc-list-group-item list-group-item-action">
                            <div class="fc-icon fc-icon-grupos"><span>Groups</span></div>  
                        </a>
                        <a href="#" class="list-group-item fc-list-group-item list-group-item-action">
                            <div class="fc-icon fc-icon-paginas"><span>Pages</span></div>  
                        </a>
                        <a href="#" class="list-group-item fc-list-group-item list-group-item-action">
                            <div class="fc-icon fc-icon-eventos"><span>Events</span></div>  
                        </a>
                        <a href="#" class="list-group-item fc-list-group-item list-group-item-action">See more...</a>
                    </div>                    
                </div>
                <div class="col-md-6">
                    <br/>
                    <div class="row">
                        <div class="col-md-12">
                                <div class="card">
                                        <div class="card-header fc-card-header">
                                            Create post
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2" style="text-align: right">
                                                <img src="images/profile-default.png" class="rounded-circle" style="width: 44px; margin-top: 10px;"/>
                                            </div>
                                            <div class="col-md-10">
                                                <textarea class="form-control" placeholder="What are you thinking, Luciano?"></textarea>
                                            </div>
                                        </div>
                                        <hr/>
                                        
                                        <div class="row" style="margin-left: 0px;">
                                            <div class="col-md-3">
                                                <button class="fc-btn fc-btn-rounded">
                                                    <div class="fc-icon fc-icon-foto-video">
                                                        <label>Photo/video</label>
                                                    </div>                                    
                                                </button>
                                            </div>
                                            <div class="col-md-3">
                                                <button class="fc-btn fc-btn-rounded">
                                                    <div class="fc-icon fc-icon-marcar-amigos">
                                                        <label>Tag friends</label>
                                                    </div>                                    
                                                </button>
                                            </div>
                                            <div class="col-md-3">
                                                <button class="fc-btn fc-btn-rounded">
                                                    <div class="fc-icon fc-icon-sentimentos">
                                                        <label>Feeling/...</label>
                                                    </div>                                    
                                                </button>
                                            </div>   
                                            <div class="col-md-2">
                                                <button class="fc-btn fc-btn-rounded" style="width: 40px;">
                                                    <div class="fc-icon fc-icon-mais"></div>                                   
                                                </button>
                                            </div> 
                                        </div>
                                        <br/>              
                                        </div>
                                        </div>
                                        </div>
                                        <br/>
                                        <!--Vyberanie príspevkov a zobrazovanie na webstránke-->
                                        <?php
                                        include_once 'functions.php';
                                        $conn = connectToDatabase('localhost', 'social_network', 'root', 'heslo');
                                        $posts = fetchPostsFromDatabase($conn);
                                        foreach ($posts as $post) {
                                            $post->renderPost();
                                        }
                                        $conn = null;
                                        ?>


                                        <br/>          
                                        </div>
                                        <div class="col-md-4">
                                            <br/>
                                            <div class="card">
                                                <div class="card-header fc-card-header-secondary">
                                                    Friend requests
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-2" style="text-align: right">
                                                            <img src="images/profile-default.png" class="rounded-circle" style="width: 44px; margin-top: 10px"/>
                                                        </div>
                                                        <div class="col-md-10">
                                                            <div style="margin-top: 10px">
                                                                <a href="#">Lorem ipsum dolor</a><br/>
                                                                <span>3 mutual friends</span><br/>
                                                                <button class="fc-btn fc-btn-default">Confirm</button>
                                                                <button class="fc-btn fc-btn-default">Delete</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-2" style="text-align: right">
                                                            <img src="images/profile-default.png" class="rounded-circle" style="width: 44px; margin-top: 10px"/>
                                                        </div>
                                                        <div class="col-md-10">
                                                            <div style="margin-top: 10px">
                                                                <a href="#">Lorem ipsum dolor</a><br/>
                                                                <span>10 mutual friends</span><br/>
                                                                <button class="fc-btn fc-btn-default">Confirm</button>
                                                                <button class="fc-btn fc-btn-default">Delete</button>
                                                            </div>
                                                        </div>
                                                    </div>     
                                                    <div class="row">
                                                        <div class="col-md-2" style="text-align: right">
                                                            <img src="images/profile-default.png" class="rounded-circle" style="width: 44px; margin-top: 10px"/>
                                                        </div>
                                                        <div class="col-md-10">
                                                            <div style="margin-top: 10px">
                                                                <a href="#">Lorem ipsum dolor</a><br/>
                                                                <span>5 mutual friends</span><br/>
                                                                <button class="fc-btn fc-btn-default">Confirm</button>
                                                                <button class="fc-btn fc-btn-default">Delete</button>
                                                            </div>
                                                        </div>
                                                    </div>                       
                                                </div>
                                            </div>
                                            <br/>
                                            <div class="card">
                                                <div class="card-header fc-card-header-secondary">
                                                    People you may know
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-2" style="text-align: right">
                                                            <img src="images/profile-default.png" class="rounded-circle" style="width: 44px; margin-top: 10px"/>
                                                        </div>
                                                        <div class="col-md-10">
                                                            <div style="margin-top: 10px">
                                                                <a href="#">Lorem ipsum dolor</a><br/>
                                                                <button class="fc-btn fc-btn-default">Confirm</button>
                                                                <button class="fc-btn fc-btn-default">Delete</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-2" style="text-align: right">
                                                            <img src="images/profile-default.png" class="rounded-circle" style="width: 44px; margin-top: 10px"/>
                                                        </div>
                                                        <div class="col-md-10">
                                                            <div style="margin-top: 10px">
                                                                <a href="#">Lorem ipsum dolor</a><br/>
                                                                <button class="fc-btn fc-btn-default">Confirm</button>
                                                                <button class="fc-btn fc-btn-default">Delete</button>
                                                            </div>
                                                        </div>
                                                    </div>                            
                                                </div>
                                            </div>
                                            <br/>
                                            <div class="card">
                                                <div class="card-header fc-card-header-secondary">
                                                    Sponsored
                                                </div>
                                                <div class="card-body">
                                                    <img src="images/banner-default.png"/>
                                                    <br/><br/>
                                                    <a href="#">Lorem ipsum dolor sit amet</a><br/>
                                                    <span>
                                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam in feugiat mauris. Maecenas nec pharetra arcu. Cras eleifend posuere dui, in molestie eros placerat vel.
                                                    </span>
                                                </div>
                                            </div>
                                        
                                            <br/>
                                            <div class="card">
                                                <div class="card-body">
                                                    <span>Portuguese (Brazil)</span> · <a href="#">Portuguese (Portugal)</a> · <a href="#">English (US)</a> · 
                                                    <a href="#">Spanish (Spain)</a> · <a href="#">French (France)</a>
                                                </div>
                                            </div>
                                            <br/>
                                        
                                            <?php
                                                include 'footer.php'
                                            ?>
                                        </div>
                                        </div>
                                        </div>
                                        