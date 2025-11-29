<?php
session_start();
require_once(__DIR__ . '/../settings/core.php');
require_once(__DIR__ . '/../settings/db_class.php');

if (!isLoggedIn()) { header('Location: login.php'); exit; }

$db = new db_connection();
// Fetch regions only (no staple food logic)
$regions = $db->db_fetch_all(
    "SELECT region_id, region_name, description
       FROM regions
       ORDER BY region_name"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regions - Burundi Foods</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body{
            margin:0;
            font-family:system-ui,-apple-system,Segoe UI,Roboto;
            background:#e5e7eb;
            color:#111827;
            padding:32px 16px;
            display:flex;
            justify-content:center;
        }
        .container{
            width:100%;
            max-width:1100px;
            background:#f9fafb;
            border-radius:18px;
            padding:24px 20px 28px;
            box-shadow:0 14px 35px rgba(15,23,42,0.15);
        }
        h1{
            font-size:2rem;
            margin:0 0 8px;
            font-weight:700;
            display:flex;
            align-items:center;
            gap:10px;
            color:#111827;
        }
        h1 i{color:#22c55e;}
        .subtitle{color:#6b7280;margin-bottom:20px;font-size:0.95rem;}
        .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:18px;margin-top:8px;}
        .card{
            border-radius:16px;
            padding:18px 16px 16px;
            background:#ffffff;
            color:#111827;
            position:relative;
            overflow:hidden;
            display:flex;
            flex-direction:column;
            justify-content:space-between;
            box-shadow:0 8px 20px rgba(15,23,42,0.06);
            border:1px solid #e5e7eb;
        }
        .card::before{
            content:"";
            position:absolute;
            inset:auto -40px -40px auto;
            width:130px;height:130px;
            background:radial-gradient(circle at center,rgba(56,189,248,0.16),transparent 70%);
            opacity:0.9;
        }
        .card-inner{position:relative;z-index:1;}
        .region-label{font-size:0.78rem;text-transform:uppercase;letter-spacing:0.08em;color:#9ca3af;margin-bottom:6px;}
        .card-title{font-size:1.25rem;font-weight:700;margin-bottom:6px;display:flex;align-items:center;gap:10px;}
        .card-title span{letter-spacing:0.02em;}
        .card-title i{color:#22c55e;}
        .chip-row{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:10px;}
        .chip{
            font-size:0.75rem;
            padding:4px 10px;
            border-radius:999px;
            background:#f3f4f6;
            border:1px solid #e5e7eb;
            color:#4b5563;
        }
        .chip-highlight{background:#dcfce7;border-color:#bbf7d0;color:#166534;}
        .card-desc{font-size:0.9rem;color:#6b7280;margin-bottom:12px;max-height:3.4em;overflow:hidden;}
        .btn{
            display:inline-flex;align-items:center;gap:6px;padding:8px 13px;border-radius:999px;border:none;
            background:#111827;color:#f9fafb;font-size:0.9rem;text-decoration:none;cursor:pointer;font-weight:600;
            box-shadow:0 8px 18px rgba(15,23,42,0.25);
        }
        .btn i{font-size:0.8rem;}
        .btn:hover{background:#1f2937;}
        .top-bar{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;flex-wrap:wrap;gap:8px;}
        .back-link{font-size:0.9rem;color:#374151;text-decoration:none;display:inline-flex;align-items:center;gap:6px;padding:6px 10px;border-radius:999px;background:#e5e7eb;}
        .back-link i{color:#111827;}
        @media (max-width:640px){body{padding:16px 10px;}.container{padding:18px 14px 20px;}}
    </style>
</head>
<body>
<div class="container">
    <div class="top-bar">
        <h1><i class="fa fa-map-location-dot"></i><span>Discover Burundi by Region</span></h1>
        <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
            <a href="#" onclick="history.back();return false;" class="back-link"><i class="fa fa-arrow-left"></i><span>Back</span></a>
            <a href="../index.php" class="back-link"><i class="fa fa-home"></i><span>Home</span></a>
            <a href="all_product.php" class="back-link"><i class="fa fa-utensils"></i><span>All foods</span></a>
        </div>
    </div>
    <p class="subtitle">Choose a region to explore traditional dishes, restaurants and street food. Images are always visible; full details unlock after subscription.</p>
    <div class="grid">
        <?php if ($regions): ?>
            <?php foreach ($regions as $region): ?>
                <article class="card">
                    <div class="card-inner">
                        <div class="region-label">Burundi region</div>
                        <div class="card-title">
                            <i class="fa fa-location-dot"></i>
                            <span><?php echo htmlspecialchars($region['region_name']); ?></span>
                        </div>

                        <div class="chip-row">
                            <span class="chip chip-highlight">Travel & Food Guide</span>
                            <span class="chip">Local restaurants</span>
                            <span class="chip">Street food</span>
                        </div>

                        <?php if (!empty($region['description'])): ?>
                            <p class="card-desc"><?php echo htmlspecialchars($region['description']); ?></p>
                        <?php else: ?>
                            <p class="card-desc">Discover traditional dishes, cozy spots and street food loved by locals in this region.</p>
                        <?php endif; ?>
                    </div>
                    <a class="btn" href="all_product.php?region_id=<?php echo $region['region_id']; ?>">
                        <span>View foods in this region</span>
                        <i class="fa fa-arrow-right"></i>
                    </a>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No regions configured yet.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
