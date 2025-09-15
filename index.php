<?php
// index.php
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>IQ Test — Home</title>
  <style>
    /* Internal CSS — modern polished look */
    :root{
      --bg:#071427; --card:#0b1622; --accent:#7dd3fc; --accent2:#a3e635; --muted:#9fb3c6;
      --radius:18px; --ff: Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial;
    }
    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0; font-family:var(--ff); color:#ecf8ff;
      background:
        radial-gradient(800px 400px at 10% 10%, rgba(163,230,53,0.03), transparent 6%),
        linear-gradient(180deg,#041223 0%, #071427 100%);
      display:flex; align-items:center; justify-content:center; padding:24px;
    }
 
    .container{
      width:100%; max-width:1000px; border-radius:20px; padding:28px;
      display:grid; grid-template-columns:1fr 360px; gap:20px;
      background: linear-gradient(180deg, rgba(255,255,255,0.01), rgba(255,255,255,0.005));
      border:1px solid rgba(255,255,255,0.03);
      box-shadow: 0 20px 50px rgba(0,0,0,0.6); backdrop-filter: blur(6px);
    }
 
    .left h1{margin:0 0 8px; font-size:28px}
    .lead{color:var(--muted); margin:0 0 14px; line-height:1.5}
    .card{background:rgba(255,255,255,0.01); padding:16px; border-radius:14px; border:1px solid rgba(255,255,255,0.02)}
    .btn{
      background:linear-gradient(90deg,var(--accent), var(--accent2)); color:#042028; padding:12px 18px;
      border-radius:12px; font-weight:700; border:0; cursor:pointer; display:inline-flex; gap:10px; align-items:center;
      box-shadow:0 8px 24px rgba(11,88,121,0.12);
    }
    .meta{font-size:13px; color:var(--muted); margin-top:10px}
    .right{display:flex; flex-direction:column; gap:12px}
    .stat{display:flex; justify-content:space-between; align-items:center; gap:12px}
    .num{font-weight:800; color:var(--accent)}
    footer{grid-column:1/-1; text-align:center; color:var(--muted); margin-top:8px; font-size:13px}
 
    @media (max-width:920px){
      .container{grid-template-columns:1fr; padding:18px}
      .right{order:3}
    }
  </style>
</head>
<body>
  <main class="container">
    <section class="left">
      <h1>Intelligence Quotient (IQ) Test</h1>
      <p class="lead">A short cognitive assessment of logical reasoning, numerical ability, and pattern recognition. This test estimates your current problem-solving strengths and suggests ways to improve.</p>
 
      <div class="card">
        <strong>What to expect</strong>
        <ul style="margin:10px 0 12px; color:var(--muted)">
          <li>20 multiple-choice questions (mix of logic, numbers, and patterns)</li>
          <li>~10–15 minutes • No account required • Results stored locally</li>
        </ul>
 
        <div style="display:flex; gap:12px; align-items:center;">
          <button class="btn" id="startBtn">▶ Start Test</button>
          <div class="meta">Estimate of cognitive strengths • Shareable & downloadable</div>
        </div>
      </div>
 
      <p style="margin-top:14px; color:var(--muted)">Tip: Work in a quiet place and avoid external help for best results — honesty gives better feedback.</p>
    </section>
 
    <aside class="right">
      <div class="card stat">
        <div>
          <div style="font-size:13px;color:var(--muted)">Areas covered</div>
          <div class="num">Logical • Numerical • Pattern</div>
        </div>
        <div style="text-align:right">
          <div style="font-size:13px;color:var(--muted)">Questions</div>
          <div class="num">20</div>
        </div>
      </div>
 
      <div class="card">
        <div style="font-weight:700">Scoring</div>
        <div style="color:var(--muted); margin-top:6px">Each correct answer = 1 point. Raw score is converted to an estimated IQ range (70–130) shown on the results page.</div>
      </div>
 
      <div class="card" style="text-align:center">
        <div style="font-weight:700">Ready for the challenge?</div>
        <div style="color:var(--muted); margin-top:6px">You can retake the test anytime. Results are private on your device.</div>
      </div>
    </aside>
 
    <footer>Save the files: index.php, quiz.php, result.php — all internal CSS & JS (no external files)</footer>
  </main>
 
  <script>
    document.getElementById('startBtn').addEventListener('click', function(){
      window.location.href = 'quiz.php';
    });
  </script>
</body>
</html>
 
