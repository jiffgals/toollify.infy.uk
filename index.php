<?php
session_start();

// single DB connection
$conn = new mysqli(
    "sql113.infinityfree.com",
    "if0_39630925",
    "bgr8o2wrUF29",
    "if0_39630925_db_tool"
);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

/* =========================
   AJAX: comments handlers
   ========================= */

// dictionary comments (modal 4)
if (isset($_GET['get_comments4'])) {
    $res = $conn->query("SELECT username, comment, created_at FROM dictionary_comments ORDER BY created_at DESC");
    if ($res && $res->num_rows > 0) {
        while ($r = $res->fetch_assoc()) {
            echo "<p><strong>" . htmlspecialchars($r['username']) . ":</strong> " .
                 nl2br(htmlspecialchars($r['comment'])) .
                 "<br><small>" . $r['created_at'] . "</small></p><hr>";
        }
    } else {
        echo "<p>No comments yet.</p>";
    }
    exit;
}

// reddit generator comments (modal 3)
if (isset($_GET['get_comments3'])) {
    $res = $conn->query("SELECT username, comment, created_at FROM redditgen_comments ORDER BY created_at DESC");
    if ($res && $res->num_rows > 0) {
        while ($r = $res->fetch_assoc()) {
            echo "<p><strong>" . htmlspecialchars($r['username']) . ":</strong> " .
                 nl2br(htmlspecialchars($r['comment'])) .
                 "<br><small>" . $r['created_at'] . "</small></p><hr>";
        }
    } else {
        echo "<p>No comments yet.</p>";
    }
    exit;
}

// tweet generator comments (modal 2)
if (isset($_GET['get_comments2'])) {
    $res = $conn->query("SELECT username, comment, created_at FROM tweetgen_comments ORDER BY created_at DESC");
    if ($res && $res->num_rows > 0) {
        while ($r = $res->fetch_assoc()) {
            echo "<p><strong>" . htmlspecialchars($r['username']) . ":</strong> " .
                 nl2br(htmlspecialchars($r['comment'])) .
                 "<br><small>" . $r['created_at'] . "</small></p><hr>";
        }
    } else {
        echo "<p>No comments yet.</p>";
    }
    exit;
}

// hashtagger comments (modal)
if (isset($_GET['get_comments'])) {
    $res = $conn->query("SELECT username, comment, created_at FROM hashtagger_comments ORDER BY created_at DESC");
    if ($res && $res->num_rows > 0) {
        while ($r = $res->fetch_assoc()) {
            echo "<p><strong>" . htmlspecialchars($r['username']) . ":</strong> " .
                 nl2br(htmlspecialchars($r['comment'])) .
                 "<br><small>" . $r['created_at'] . "</small></p><hr>";
        }
    } else {
        echo "<p>No comments yet.</p>";
    }
    exit;
}

// name generator comments (modal 1)
if (isset($_GET['get_comments1'])) {
    $res = $conn->query("SELECT username, comment, created_at FROM namegen_comments ORDER BY created_at DESC");
    if ($res && $res->num_rows > 0) {
        while ($r = $res->fetch_assoc()) {
            echo "<p><strong>" . htmlspecialchars($r['username']) . ":</strong> " .
                 nl2br(htmlspecialchars($r['comment'])) .
                 "<br><small>" . $r['created_at'] . "</small></p><hr>";
        }
    } else {
        echo "<p>No comments yet.</p>";
    }
    exit;
}




// return dictionary like count (used after toggle)
if (isset($_GET['get_dictionary_likes'])) {
    $res = $conn->query("SELECT COUNT(*) AS cnt FROM dictionary_likes");
    $count = 0;
    if ($res && $row = $res->fetch_assoc()) $count = (int)$row['cnt'];
    echo $count;
    exit;
}

// return reddit generator like count (used after toggle)
if (isset($_GET['get_reddit_likes'])) {
    $res = $conn->query("SELECT COUNT(*) AS cnt FROM reddit_likes");
    $count = 0;
    if ($res && $row = $res->fetch_assoc()) $count = (int)$row['cnt'];
    echo $count;
    exit;
}

// return tweetgen like count (used after toggle)
if (isset($_GET['get_tweetgen_likes'])) {
    $res = $conn->query("SELECT COUNT(*) AS cnt FROM tweetgen_likes");
    $count = 0;
    if ($res && $row = $res->fetch_assoc()) $count = (int)$row['cnt'];
    echo $count;
    exit;
}

// return hashtag generator like count (used after toggle)
if (isset($_GET['get_hashtag_likes'])) {
    $res = $conn->query("SELECT COUNT(*) AS cnt FROM hashtag_generator_likes");
    $count = 0;
    if ($res && $row = $res->fetch_assoc()) $count = (int)$row['cnt'];
    echo $count;
    exit;
}

// return name generator like count (used after toggle)
if (isset($_GET['get_name_likes'])) {
    $res = $conn->query("SELECT COUNT(*) AS cnt FROM name_generator_likes");
    $count = 0;
    if ($res && $row = $res->fetch_assoc()) $count = (int)$row['cnt'];
    echo $count;
    exit;
}


/* =========================
   Regular page load: counts
   ========================= */

$commentCount4 = 0;
$res = $conn->query("SELECT COUNT(*) AS total FROM dictionary_comments");
if ($res && $r = $res->fetch_assoc()) $commentCount4 = (int)$r['total'];

$commentCount3 = 0;
$res = $conn->query("SELECT COUNT(*) AS total FROM redditgen_comments");
if ($res && $r = $res->fetch_assoc()) $commentCount3 = (int)$r['total'];

$commentCount2 = 0;
$res = $conn->query("SELECT COUNT(*) AS total FROM tweetgen_comments");
if ($res && $r = $res->fetch_assoc()) $commentCount2 = (int)$r['total'];

$commentCount = 0; // hashtagger
$res = $conn->query("SELECT COUNT(*) AS total FROM hashtagger_comments");
if ($res && $r = $res->fetch_assoc()) $commentCount = (int)$r['total'];

$commentCount1 = 0; // namegen
$res = $conn->query("SELECT COUNT(*) AS total FROM namegen_comments");
if ($res && $r = $res->fetch_assoc()) $commentCount1 = (int)$r['total'];



/* dictionary likes info */
$dictionaryLikes = 0;
$res = $conn->query("SELECT COUNT(*) AS cnt FROM dictionary_likes");
if ($res && $r = $res->fetch_assoc()) $dictionaryLikes = (int)$r['cnt'];

$userLiked = false;
if (isset($_SESSION['username'])) {
    $stmt = $conn->prepare("SELECT id FROM dictionary_likes WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $stmt->store_result();
    $userLiked = ($stmt->num_rows > 0);
    $stmt->close();
}

/* reddit likes info */
$redditLikes = 0;
$res = $conn->query("SELECT COUNT(*) AS cnt FROM reddit_likes");
if ($res && $r = $res->fetch_assoc()) $redditLikes = (int)$r['cnt'];

$userLiked = false;
if (isset($_SESSION['username'])) {
    $stmt = $conn->prepare("SELECT id FROM reddit_likes WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $stmt->store_result();
    $userLiked = ($stmt->num_rows > 0);
    $stmt->close();
}

/* tweetgen likes info */
$tweetgenLikes = 0;
$res = $conn->query("SELECT COUNT(*) AS cnt FROM tweetgen_likes");
if ($res && $r = $res->fetch_assoc()) $tweetgenLikes = (int)$r['cnt'];

$userLiked = false;
if (isset($_SESSION['username'])) {
    $stmt = $conn->prepare("SELECT id FROM tweetgen_likes WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $stmt->store_result();
    $userLiked = ($stmt->num_rows > 0);
    $stmt->close();
}

/* Hashtag generator likes info */
$hashtagLikes = 0;
$res = $conn->query("SELECT COUNT(*) AS cnt FROM hashtag_generator_likes");
if ($res && $r = $res->fetch_assoc()) $hashtagLikes = (int)$r['cnt'];

$userLiked = false;
if (isset($_SESSION['username'])) {
    $stmt = $conn->prepare("SELECT id FROM hashtag_generator_likes WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $stmt->store_result();
    $userLiked = ($stmt->num_rows > 0);
    $stmt->close();
}

/* Name generator likes info */
$nameLikes = 0;
$res = $conn->query("SELECT COUNT(*) AS cnt FROM name_generator_likes");
if ($res && $r = $res->fetch_assoc()) $nameLikes = (int)$r['cnt'];

$userLiked = false;
if (isset($_SESSION['username'])) {
    $stmt = $conn->prepare("SELECT id FROM name_generator_likes WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $stmt->store_result();
    $userLiked = ($stmt->num_rows > 0);
    $stmt->close();
}




/* =========================
   Feed posts insert + fetch
   ========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    if (!isset($_SESSION['username'])) {
        echo "<script>alert('Please log in to comment.');</script>";
    } else {
        $username = $_SESSION['username'];
        $comment = trim($_POST['comment']);
        if ($comment !== '') {
            $stmt = $conn->prepare("INSERT INTO feed_posts (username, comment) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $comment);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "<script>alert('Comment cannot be empty.');</script>";
        }
    }
}

$res = $conn->query("SELECT * FROM feed_posts ORDER BY created_at DESC");
$comments = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Toollify — Internet Tools Hub</title>
  <style>
    :root {
      --primary-color: #4f46e5;
      --bg-light: #f9fafb;
      --bg-dark: #1f2937;
      --text-dark: #111827;
      --text-light: #6b7280;
      --text-lightmode: #ffffff;
      --card-bg-light: #ffffff;
      --card-bg-dark: #374151;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: var(--bg-light);
      color: var(--text-dark);
      padding: 3px;
      transition: background 0.3s, color 0.3s;
    }
    body.dark-mode { background-color: var(--bg-dark); color: var(--text-lightmode); }
    header { background-color: ; max-width:100%; margin:40px auto; padding:20px; border: 1px solid #ddd; border-radius:10px; text-align: center; margin-bottom: 40px; }
    header h1 { font-size: 2.5rem; color: var(--primary-color); margin-bottom: 10px; }
    header p { font-size: 1.1rem; color: var(--text-light); }
    .dark-mode header p { color: #d1d5db; }
    .toggle-dark {
      //position: absolute; top: 20px; right: 20px;
      float: right; background: var(--primary-color); color: white; border: none; margin-right: 2px;
      padding: 8px 8px; border-radius: 50px; cursor: pointer; font-size: 0.9rem;
    }
    .feed-container { max-width:100%; margin:40px auto; padding:20px; border: 1px solid #ddd; border-radius:10px;
    }
    .tool-cont { width:100%; margin:40px auto; padding:20px; border: 1px solid #bbb; border-radius:10px;
    }
    .tool-grid { display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; }
    .tool-card {
      background-color: var(--card-bg-light); border-radius: 12px; padding: 20px;
      width: 220px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); text-align: center;
      transition: transform 0.2s, background 0.3s;
      position: relative;
    }
    body.dark-mode .tool-card { background-color: var(--card-bg-dark); }
    .tool-card:hover { transform: translateY(-5px); }
    .tool-card .icon { font-size: 2.5rem; margin-bottom: 10px; }
    .tool-card h2 { font-size: 1.25rem; margin-bottom: 10px; color: var(--primary-color); }
    .tool-card p { font-size: 0.95rem; color: var(--text-light); margin-bottom: 15px; }
    body.dark-mode .tool-card p { color: #d1d5db; }
    .tool-card a {
      display: inline-block; padding: 10px 16px; background-color: var(--primary-color);
      color: white; text-decoration: none; border-radius: 8px; font-weight: bold;
    }
    .tool-card a:hover { background-color: #3730a3; }
    footer { margin-top: 50px; text-align: center; font-size: 0.9rem; color: var(--text-light); }
    body.dark-mode footer { color: #9ca3af; }
    @media (max-width: 600px) { .tool-card { width: 90%; } }

    .tool-card .meta { position: absolute; bottom: 12px; left: 16px; right: 16px; display:flex; justify-content:flex-start; gap:10px; align-items:center; }
    .meta .badge { background: #eee; padding: 4px 8px; border-radius: 12px; font-size: 12px; cursor: pointer; display:inline-flex; gap:6px; align-items:center; }
    body.dark-mode .badge { background-color: var(--bg-dark); color: var(--text-light); }
    body.dark-mode .pop { background-color: var(--bg-dark); color: var(--text-light); }
    .heart { font-size:14px; color:#888; transition: color .2s ease; user-select:none; }
    .heart.liked { color: #e11d48; } /* red */
    .heart1 { font-size:14px; color:#888; transition: color .2s ease; user-select:none; }
    .heart1.liked { color: #e11d48; } /* red */
    .heart2 { font-size:14px; color:#888; transition: color .2s ease; user-select:none; }
    .heart2.liked { color: #e11d48; } /* red */
    .heart3 { font-size:14px; color:#888; transition: color .2s ease; user-select:none; }
    .heart3.liked { color: #e11d48; } /* red */
    .heart4 { font-size:14px; color:#888; transition: color .2s ease; user-select:none; }
    .heart4.liked { color: #e11d48; } /* red */

    
    .login { float: right; background-color: var(--primary-color); color: #fff; border-radius: 50px; padding: 8px 8px; margin-top: -0.8px; margin-right: 4px; }
    body.dark-mode .login { background-color: var(--card-bg-dark); color: var(--text-lightmode); }
    .logout { float: right; background-color: var(--text-light); color: #fff; border-radius: 50px; padding: 8px 8px; margin-top: -0.8px; }
    body.dark-mode .logout { background-color: var(--card-bg-dark); color: var(--text-lightmode); }
    .fddo { color: var(--text-red); text-decoration:none; }
    body.dark-mode .fddo { color: var(--text-red); text-decoration:none; }
    .fddi { color: var(--text-grn); text-decoration:none; }
    body.dark-mode .fddi { color: var(--text-grn); text-decoration:none; }
    .headsticky { background-color: transparent; width:100%; padding:4px; border: 0px solid #ddd; border-radius:10px; position: fixed; }
  </style>
</head>
<body>

<div class="headsticky">
<?php if(isset($_SESSION['username'])): ?>
<div class="logout">
<p><a href="../logout.php" class="fddo">🟠</a></p>
</div>
<?php else: ?>
<div class="login">
<p><a href="../login.php" class="fddi">🟢</a></p>
</div>
<?php endif; ?>

  <button class="toggle-dark" onclick="toggleDarkMode()">🌙</button>

</div>

<script>
    // Apply saved mode on page load
    document.addEventListener("DOMContentLoaded", function() {
        if (localStorage.getItem("theme") === "dark") {
            document.body.classList.add("dark-mode");
        }
    });

    // Toggle mode and save to localStorage
    function toggleDarkMode() {
        document.body.classList.toggle("dark-mode");
        if (document.body.classList.contains("dark-mode")) {
            localStorage.setItem("theme", "dark");
        } else {
            localStorage.setItem("theme", "light");
        }
    }
</script>

<header>
  <h1>🔧 Toollify</h1>
  <p>Your all-in-one free internet tools hub</p>
</header>

<div class="tool-cont">
<main class="tool-grid">

  <div class="tool-card">
    <div class="icon">📚</div>
    <h2>Dictionary</h2>
    <p>Navigate Dictionary to get references from specific words easily.</p>
    <a href="tools/dict.php">Try It</a>

    <div class="meta">
      <div id="viewComments4" class="badge">🗨️ <?php echo $commentCount4; ?></div>

      <!-- Like badge -->
      <div id="likeBtn4" class="badge" style="margin-left:8px;">
        <span id="heart4" class="heart4 <?php echo $userLiked ? 'liked' : ''; ?>">❤</span>
        <span id="likeCount4" style="margin-left:4px;"><?php echo $dictionaryLikes; ?></span>
      </div>
    </div>
  </div>

  <div class="tool-card">
    <div class="icon">📰</div>
    <h2>Reddit Generator</h2>
    <p>Type a topic and generate creative Reddit-style post ideas instantly.</p>
    <a href="tools/reddit_generator.php">Try It</a>

    <div class="meta">
      <div id="viewComments3" class="badge">🗨️ <?php echo $commentCount3; ?></div>

      <!-- Like badge -->
      <div id="likeBtn3" class="badge" style="margin-left:8px;">
        <span id="heart3" class="heart3 <?php echo $userLiked ? 'liked' : ''; ?>">❤</span>
        <span id="likeCount3" style="margin-left:4px;"><?php echo $redditLikes; ?></span>
      </div>
    </div>
  </div>

  <div class="tool-card">
    <div class="icon">🕊️</div>
    <h2>Tweet Generator</h2>
    <p>Create smart, engaging tweets with a single click using AI.</p>
    <a href="/tools/tweetgen.php">Try It</a>

    <div class="meta">
      <div id="viewComments2" class="badge">🗨️ <?php echo $commentCount2; ?></div>

      <!-- Like badge -->
      <div id="likeBtn2" class="badge" style="margin-left:8px;">
        <span id="heart2" class="heart2 <?php echo $userLiked ? 'liked' : ''; ?>">❤</span>
        <span id="likeCount2" style="margin-left:4px;"><?php echo $tweetgenLikes; ?></span>
      </div>
    </div>
  </div>

  <div class="tool-card">
    <div class="icon">🏷️</div>
    <h2>Hashtag Generator</h2>
    <p>Generate trending and smart hashtags for your posts or campaigns.</p>
    <a href="tools/hashtagger.php">Try It</a>     <br><br><br>

    <div class="meta">
      <div id="viewComments" class="badge">🗨️ <?php echo $commentCount; ?></div>

      <!-- Like badge -->
      <div id="likeBtn" class="badge" style="margin-left:8px;">
        <span id="heart" class="heart <?php echo $userLiked ? 'liked' : ''; ?>">❤</span>
        <span id="likeCount" style="margin-left:4px;"><?php echo $hashtagLikes; ?></span>
      </div>
    </div>
  </div>

  <div class="tool-card">
    <div class="icon">📛</div>
    <h2>Name Generator</h2>
    <p>Quickly generate unique English names for account creation or client requests.</p>
    <a href="tools/name_generator.php">Try It</a>     <br><br><br>

    <div class="meta">
      <!-- Comments badge (opens modal) -->
      <div id="viewComments1" class="badge">🗨️ <?php echo $commentCount1; ?></div>

      <!-- Like badge -->
      <div id="likeBtn1" class="badge" style="margin-left:8px;">
        <span id="heart1" class="heart1 <?php echo $userLiked ? 'liked' : ''; ?>">❤</span>
        <span id="likeCount1" style="margin-left:4px;"><?php echo $nameLikes; ?></span>
      </div>
    </div>
  </div>

</main>
</div>

<!-- modals -->
<!-- dictionary modal 4 -->
<div id="commentModal4" class="pop" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
    background:rgba(0,0,0,0.6); justify-content:center; align-items:center; z-index:1000;">
  <div style="background:#fff; padding:20px; width:500px; max-height:80%; overflow-y:auto; border-radius:8px; position:relative;">
    <span id="closeModal4" style="position:absolute; top:10px; right:15px; cursor:pointer; font-size:20px;">&times;</span>
    <h3>💬 Comments</h3>
    <div id="commentContent4">Loading...</div>
  </div>
</div>

<!-- reddit modal 3 -->
<div id="commentModal3" class="pop" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
    background:rgba(0,0,0,0.6); justify-content:center; align-items:center; z-index:1000;">
  <div style="background:#fff; padding:20px; width:500px; max-height:80%; overflow-y:auto; border-radius:8px; position:relative;">
    <span id="closeModal3" style="position:absolute; top:10px; right:15px; cursor:pointer; font-size:20px;">&times;</span>
    <h3>💬 Comments</h3>
    <div id="commentContent3">Loading...</div>
  </div>
</div>

<!-- tweet modal 2 -->
<div id="commentModal2" class="pop" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
    background:rgba(0,0,0,0.6); justify-content:center; align-items:center; z-index:1000;">
  <div style="background:#fff; padding:20px; width:500px; max-height:80%; overflow-y:auto; border-radius:8px; position:relative;">
    <span id="closeModal2" style="position:absolute; top:10px; right:15px; cursor:pointer; font-size:20px;">&times;</span>
    <h3>💬 Comments</h3>
    <div id="commentContent2">Loading...</div>
  </div>
</div>

<!-- hashtagger modal -->
<div id="commentModal" class="pop" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
    background:rgba(0,0,0,0.6); justify-content:center; align-items:center; z-index:1000;">
  <div style="background:#fff; padding:20px; width:500px; max-height:80%; overflow-y:auto; border-radius:8px; position:relative;">
    <span id="closeModal" style="position:absolute; top:10px; right:15px; cursor:pointer; font-size:20px;">&times;</span>
    <h3>💬 Comments</h3>
    <div id="commentContent">Loading...</div>
  </div>
</div>

<!-- namegen modal -->
<div id="commentModal1" class="pop" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
    background:rgba(0,0,0,0.6); justify-content:center; align-items:center; z-index:1000;">
  <div style="background:#fff; padding:20px; width:500px; max-height:80%; overflow-y:auto; border-radius:8px; position:relative;">
    <span id="closeModal1" style="position:absolute; top:10px; right:15px; cursor:pointer; font-size:20px;">&times;</span>
    <h3>💬 Comments</h3>
    <div id="commentContent1">Loading...</div>
  </div>
</div>

<!-- feed section -->
<div id="feed-container" class="feed-container">
<h3>💬 Comments</h3>

<?php if(isset($_SESSION['username'])): ?>
<form method="POST" style="margin-bottom:20px;">
    <textarea name="comment" placeholder="Write your comment..." 
        style="width:100%;padding:10px;border-radius:5px;" required></textarea>
    <button type="submit" 
        style="margin-top:10px;padding:8px 16px;background:#4f46e5;color:#fff;border:none;border-radius:5px;">
        Post Comment
    </button>
</form>
    <!--<p style="margin-bottom:20px;"><a href="logout.php">Logout</a></p>-->
<?php else: ?>
    <!--<p><a href="../login.php">Login</a>--> Click 🟢 above to Login to post a comment. <!--</p>-->
<?php endif; ?>

<div>
<?php foreach($comments as $c): ?>
    <div style="padding:10px;border-bottom:1px solid #ddd;">
        <strong><?= htmlspecialchars($c['username']) ?></strong> 
        <small><?= $c['created_at'] ?></small>
        <p id="comment-text-<?= $c['id'] ?>"><?= nl2br(htmlspecialchars($c['comment'])) ?></p>

        <?php if(isset($_SESSION['username']) && $_SESSION['username'] === $c['username']): ?>
            <button onclick="editComment(<?= $c['id'] ?>, '<?= htmlspecialchars($c['comment'], ENT_QUOTES) ?>')" style="margin-right:5px;">Edit</button>
            <button onclick="deleteComment(<?= $c['id'] ?>)" style="color:red;">Delete</button>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
</div>
</div>


<script>
function editComment(id, oldText) {
    let newText = prompt("Edit your comment:", oldText);
    if (newText === null) return;
    fetch('edit_post.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'id=' + encodeURIComponent(id) + '&comment=' + encodeURIComponent(newText)
    })
    .then(res => res.text())
    .then(msg => {
        alert(msg);
        if (msg === "Updated") {
            document.getElementById('comment-text-' + id).innerText = newText;
        }
    });
}

function deleteComment(id) {
    if (!confirm("Delete this comment?")) return;
    fetch('delete_post.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'id=' + encodeURIComponent(id)
    })
    .then(res => res.text())
    .then(msg => {
        alert(msg);
        if (msg === "Deleted") {
            location.reload();
        }
    });
}
</script>

<script>
/* Utility: safe getElement */
function $id(id){ return document.getElementById(id); }

/* Dictionary Comments modals loading */
if ($id('viewComments4')) {
  $id('viewComments4').addEventListener('click', function(){
    $id('commentModal4').style.display = 'flex';
    $id('commentContent4').innerHTML = '<p>Loading...</p>';
    fetch('index.php?get_comments4=1').then(r=>r.text()).then(txt=>$id('commentContent4').innerHTML = txt).catch(()=>$id('commentContent4').innerHTML = '<p>Error loading comments.</p>');
  });
  $id('closeModal4').addEventListener('click', ()=> $id('commentModal4').style.display='none');
  window.addEventListener('click', function(e){ if(e.target == $id('commentModal4')) $id('commentModal4').style.display='none'; });
}

/* Reddit Comments modals loading */
if ($id('viewComments3')) {
  $id('viewComments3').addEventListener('click', function(){
    $id('commentModal3').style.display = 'flex';
    $id('commentContent3').innerHTML = '<p>Loading...</p>';
    fetch('index.php?get_comments3=1').then(r=>r.text()).then(txt=>$id('commentContent3').innerHTML = txt).catch(()=>$id('commentContent3').innerHTML = '<p>Error loading comments.</p>');
  });
  $id('closeModal3').addEventListener('click', ()=> $id('commentModal3').style.display='none');
  window.addEventListener('click', function(e){ if(e.target == $id('commentModal3')) $id('commentModal3').style.display='none'; });
}

/* Tweet Comments modals loading */
if ($id('viewComments2')) {
  $id('viewComments2').addEventListener('click', function(){
    $id('commentModal2').style.display = 'flex';
    $id('commentContent2').innerHTML = '<p>Loading...</p>';
    fetch('index.php?get_comments2=1').then(r=>r.text()).then(txt=>$id('commentContent2').innerHTML = txt).catch(()=>$id('commentContent2').innerHTML = '<p>Error loading comments.</p>');
  });
  $id('closeModal2').addEventListener('click', ()=> $id('commentModal2').style.display='none');
  window.addEventListener('click', function(e){ if(e.target == $id('commentModal2')) $id('commentModal2').style.display='none'; });
}

/* Hashtag Comments modals loading */
if ($id('viewComments')) {
  $id('viewComments').addEventListener('click', function(){
    $id('commentModal').style.display = 'flex';
    $id('commentContent').innerHTML = '<p>Loading...</p>';
    fetch('index.php?get_comments=1').then(r=>r.text()).then(txt=>$id('commentContent').innerHTML = txt).catch(()=>$id('commentContent').innerHTML = '<p>Error loading comments.</p>');
  });
  $id('closeModal').addEventListener('click', ()=> $id('commentModal').style.display='none');
  window.addEventListener('click', function(e){ if(e.target == $id('commentModal')) $id('commentModal').style.display='none'; });
}

/* Namegen Comments modals loading */
if ($id('viewComments1')) {
  $id('viewComments1').addEventListener('click', function(){
    $id('commentModal1').style.display = 'flex';
    $id('commentContent1').innerHTML = '<p>Loading...</p>';
    fetch('index.php?get_comments1=1').then(r=>r.text()).then(txt=>$id('commentContent1').innerHTML = txt).catch(()=>$id('commentContent1').innerHTML = '<p>Error loading comments.</p>');
  });
  $id('closeModal1').addEventListener('click', ()=> $id('commentModal1').style.display='none');
  window.addEventListener('click', function(e){ if(e.target == $id('commentModal1')) $id('commentModal1').style.display='none'; });
}




/* Like button logic for dictionary */
if ($id('likeBtn4')) {
  $id('likeBtn4').addEventListener('click', function(){
    // call your dictionary_toggle_like.php which should toggle like for current logged-in user
    fetch('dictionary_toggle_like.php', { method: 'POST' })
      .then(res => {
        try { return res.json(); } catch(e) { return Promise.resolve({status:null}); }
      })
      .then(data => {
        // preferred expected response: { status: "liked"|"unliked", count: N }
        if (data && (data.status === 'liked' || data.status === 'unliked')) {
          if (data.status === 'liked') {
            $id('heart4').classList.add('liked');
          } else {
            $id('heart4').classList.remove('liked');
          }
          if (typeof data.count !== 'undefined') {
            $id('likeCount4').textContent = data.count;
            return;
          }
        }
        // fallback: refresh like count from server
        fetch('index.php?get_dictionary_likes=1').then(r=>r.text()).then(txt => {
          $id('likeCount4').textContent = txt;
          // set heart by checking if status said liked or not
          if (data && data.status === 'liked') $id('heart4').classList.add('liked');
          if (data && data.status === 'unliked') $id('heart4').classList.remove('liked');
        }).catch(()=>{ /* ignore fetch error */ });
      })
      .catch(err => {
        console.error('Toggle like error', err);
        alert('Error toggling like. Are you logged in?');
      });
  });
}

/* Like button logic for reddit */
if ($id('likeBtn3')) {
  $id('likeBtn3').addEventListener('click', function(){
    // call your reddit_toggle_like.php which should toggle like for current logged-in user
    fetch('reddit_toggle_like.php', { method: 'POST' })
      .then(res => {
        try { return res.json(); } catch(e) { return Promise.resolve({status:null}); }
      })
      .then(data => {
        // preferred expected response: { status: "liked"|"unliked", count: N }
        if (data && (data.status === 'liked' || data.status === 'unliked')) {
          if (data.status === 'liked') {
            $id('heart3').classList.add('liked');
          } else {
            $id('heart3').classList.remove('liked');
          }
          if (typeof data.count !== 'undefined') {
            $id('likeCount3').textContent = data.count;
            return;
          }
        }
        // fallback: refresh like count from server
        fetch('index.php?get_reddit_likes=1').then(r=>r.text()).then(txt => {
          $id('likeCount3').textContent = txt;
          // set heart by checking if status said liked or not
          if (data && data.status === 'liked') $id('heart3').classList.add('liked');
          if (data && data.status === 'unliked') $id('heart3').classList.remove('liked');
        }).catch(()=>{ /* ignore fetch error */ });
      })
      .catch(err => {
        console.error('Toggle like error', err);
        alert('Error toggling like. Are you logged in?');
      });
  });
}

/* Like button logic for tweetgen */
if ($id('likeBtn2')) {
  $id('likeBtn2').addEventListener('click', function(){
    // call your tweetgen_toggle_like.php which should toggle like for current logged-in user
    fetch('tweetgen_toggle_like.php', { method: 'POST' })
      .then(res => {
        try { return res.json(); } catch(e) { return Promise.resolve({status:null}); }
      })
      .then(data => {
        // preferred expected response: { status: "liked"|"unliked", count: N }
        if (data && (data.status === 'liked' || data.status === 'unliked')) {
          if (data.status === 'liked') {
            $id('heart2').classList.add('liked');
          } else {
            $id('heart2').classList.remove('liked');
          }
          if (typeof data.count !== 'undefined') {
            $id('likeCount2').textContent = data.count;
            return;
          }
        }
        // fallback: refresh like count from server
        fetch('index.php?get_tweetgen_likes=1').then(r=>r.text()).then(txt => {
          $id('likeCount2').textContent = txt;
          // set heart by checking if status said liked or not
          if (data && data.status === 'liked') $id('heart2').classList.add('liked');
          if (data && data.status === 'unliked') $id('heart2').classList.remove('liked');
        }).catch(()=>{ /* ignore fetch error */ });
      })
      .catch(err => {
        console.error('Toggle like error', err);
        alert('Error toggling like. Are you logged in?');
      });
  });
}

/* Like button logic for Hashtag Generator */
if ($id('likeBtn')) {
  $id('likeBtn').addEventListener('click', function(){
    // call your toggle_like.php which should toggle like for current logged-in user
    fetch('hashtag_toggle_like.php', { method: 'POST' })
      .then(res => {
        try { return res.json(); } catch(e) { return Promise.resolve({status:null}); }
      })
      .then(data => {
        // preferred expected response: { status: "liked"|"unliked", count: N }
        if (data && (data.status === 'liked' || data.status === 'unliked')) {
          if (data.status === 'liked') {
            $id('heart').classList.add('liked');
          } else {
            $id('heart').classList.remove('liked');
          }
          if (typeof data.count !== 'undefined') {
            $id('likeCount').textContent = data.count;
            return;
          }
        }
        // fallback: refresh like count from server
        fetch('index.php?get_hashtag_likes=1').then(r=>r.text()).then(txt => {
          $id('likeCount').textContent = txt;
          // set heart by checking if status said liked or not
          if (data && data.status === 'liked') $id('heart').classList.add('liked');
          if (data && data.status === 'unliked') $id('heart').classList.remove('liked');
        }).catch(()=>{ /* ignore fetch error */ });
      })
      .catch(err => {
        console.error('Toggle like error', err);
        alert('Error toggling like. Are you logged in?');
      });
  });
}

/* Like button logic for Name Generator */
if ($id('likeBtn1')) {
  $id('likeBtn1').addEventListener('click', function(){
    // call your toggle_like.php which should toggle like for current logged-in user
    fetch('toggle_like.php', { method: 'POST' })
      .then(res => {
        try { return res.json(); } catch(e) { return Promise.resolve({status:null}); }
      })
      .then(data => {
        // preferred expected response: { status: "liked"|"unliked", count: N }
        if (data && (data.status === 'liked' || data.status === 'unliked')) {
          if (data.status === 'liked') {
            $id('heart1').classList.add('liked');
          } else {
            $id('heart1').classList.remove('liked');
          }
          if (typeof data.count !== 'undefined') {
            $id('likeCount1').textContent = data.count;
            return;
          }
        }
        // fallback: refresh like count from server
        fetch('index.php?get_name_likes=1').then(r=>r.text()).then(txt => {
          $id('likeCount1').textContent = txt;
          // set heart by checking if status said liked or not
          if (data && data.status === 'liked') $id('heart1').classList.add('liked');
          if (data && data.status === 'unliked') $id('heart1').classList.remove('liked');
        }).catch(()=>{ /* ignore fetch error */ });
      })
      .catch(err => {
        console.error('Toggle like error', err);
        alert('Error toggling like. Are you logged in?');
      });
  });
}

</script>

<footer>
  &copy; 2025 Toollify — Crafted on <a href="https://infinityfree.net" style="color:#6366f1;">InfinityFree</a> <a href="job/login_worker.php">Work with us</a>
</footer>

</body>
</html>
