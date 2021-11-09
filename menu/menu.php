<?php


require_once __DIR__ . '/../token.php';
$my_csrf = new csrf();

$token_key = $my_csrf->get_token_id();
$token_value = $my_csrf->get_token();

function getActive($url)
{
    $found = strpos($_SERVER['REQUEST_URI'], $url);
    echo ($found !== false) ? "active" : "";
}

?>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="<?php echo getUrl("/css/font-awesome-4.5.0/css/font-awesome.min.css") ?>">
    <link rel="stylesheet" href="<?php echo getUrl("/css/main.css") ?>">
    <script src="<?php echo getUrl("/js/theme.js") ?>" defer></script>
    <script src="<?php echo getUrl("/js/search_user.js") ?>" defer></script>
</head>

<nav>
    <header>
        <a href="<?php echo $ROOT_URL; ?>" class="navLogo">VC</a>
        <div class="navSearch">
            <!-- <div class="navSearchInputContainer">
                <div class="navSearchIcon"><i class="fa fa-search"></i></div> -->
                <input data-nav-search-input type="search" placeholder="search" />
            <!-- </div> -->
            <div data-nav-search-result-container class="navSearchResultContainer">
                <div data-nav-search-result-list class="navSearchResultList">
                    <p>Search results goes here</p>
                    <!-- SEARCH RESULT TEMPLATE USED IN JAVASCRIPT -->
                    <template data-nav-search-result-template>
                        <a href="<?php echo getUrl("/friends/profile.php"); ?>" data-nav-search-result class="navSearchResult">
                            <div class="navSearchResultUser">
                                <div class="navSearchResultUserImg">
                                    <img src="<?php echo getUrl("/images/default.png") ?>" alt="img" />
                                </div>
                                <div data-nav-search-result-username class="navSearchResultUserName">
                                    Ishimwe Valentin - valentin
                                </div>
                            </div>
                        </a>
                    </template>
                </div>
            </div>
        </div>
        <div class="navUserImage">
            <img src="<?php echo getUrl("/images/{$me->profile_pic}");  ?>" alt="profile" />
        </div>
    </header>
    <ul>
        <li title="Home">
            <a data-tooltip="Home" class="<?php getActive(getUrl("/post/home.php")); ?>" href="<?php echo getUrl("/post/home.php"); ?>"><i class="fa fa-home"></i></a>
        </li>
        <li title="Stories"><a data-tooltip="stories" class="<?php getActive(getUrl("/stories")); ?>" href="<?php echo getUrl("/stories"); ?>"><i class="fa fa-book"></i></a> </li>
        <li title="Profile"><a data-tooltip="Profile" class="<?php getActive(getUrl("/friends/profile.php")); ?>" href="<?php echo getUrl("/friends/profile.php"); ?>"><i class="fa fa-user"></i></a></li>
        <li title="Friends">
            <a data-tooltip="friends" class="<?php getActive(getUrl("/friends/friends.php")); ?>" href="<?php echo getUrl("/friends/friends.php"); ?>">
                <i class="fa fa-users"></i>
            </a>
        </li>
        <li title="Messages">
            <a data-tooltip="messages" class="<?php getActive(getUrl("/chat")); ?>" href="<?php echo getUrl("/chat"); ?>">
                <i class="fa fa-wechat"></i>
                <?php echo ($unread > 0) ? '<span class="badge danger">' . $unread . '</span>' : ''; ?>
            </a>
        </li>
        <li title="Settings">
            <a href="#"> <i class="fa fa-cog fa-fw"></i> <span></span></a>
            <ul class="subNav">
                <li>
                    <form action="<?php echo getUrl("/friends/logout.php"); ?>" method="post">
                    <input type="hidden" name="<?php echo $token_key; ?>" value="<?php echo $token_value; ?>">
                    <button style="width: 100%;"><i class="fa fa-arrow-right" style="margin-right: 10px;"></i> Logout</button>
                </form>
                </li>
                <div class="themeContainer">
                    <label for="theme-check">Light Mode</label>
                    <input type="checkbox" id="theme-check">
                    <div class="theme-scroll"></div>
                </div>
            </ul>
        </li>
    </ul>
</nav>