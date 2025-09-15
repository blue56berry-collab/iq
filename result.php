<?php
// result.php
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>IQ Test — Results</title>
  <style>
    /* Internal CSS — clear result UI */
    :root{
      --bg:#061427; --card:#071827; --accent:#60a5fa; --accent2:#ffd166; --muted:#9fb3c6;
      --radius:16px; --ff: Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial;
    }
    *{box-sizing:border-box}
    body{margin:0; font-family:var(--ff); background:linear-gradient(180deg,#041225,#072034); color:#effbff; min-height:100vh; display:flex; align-items:center; justify-content:center; padding:20px}
    .wrap{width:100%; max-width:1000px}
    .card{background:linear-gradient(180deg, rgba(255,255,255,0.01), rgba(255,255,255,0.005)); padding:22px; border-radius:20px; border:1px solid rgba(255,255,255,0.03); box-shadow:0 18px 44px rgba(0,0,0,0.6)}
    header{display:flex; justify-content:space-between; align-items:center}
    header h2{margin:0; font-size:20px}
    .scoreBox{display:flex; gap:18px; margin-top:18px; align-items:center; flex-wrap:wrap}
    .score{width:220px; height:220px; border-radius:18px; display:flex; flex-direction:column; align-items:center; justify-content:center; border:1px solid rgba(255,255,255,0.02)}
    .score .num{font-size:44px; font-weight:800; color:var(--accent)}
    .panels{flex:1; display:grid; grid-template-columns:repeat(3,1fr); gap:12px}
    .panel{padding:12px; border-radius:12px; background:rgba(255,255,255,0.01); border:1px solid rgba(255,255,255,0.02)}
    .panel h4{margin:0 0 6px; font-size:14px}
    .panel .val{font-weight:800; font-size:18px}
    .recommend{margin-top:14px; padding:12px; border-radius:12px; background:linear-gradient(90deg, rgba(96,165,250,0.03), rgba(255,209,102,0.02)); border:1px solid rgba(96,165,250,0.04)}
    .actions{display:flex; gap:10px; margin-top:12px}
    .btn{padding:10px 14px; border-radius:10px; border:0; cursor:pointer; font-weight:700}
    .btn.primary{background:linear-gradient(90deg,var(--accent), var(--accent2)); color:#08121b}
    .btn.ghost{background:transparent; color:var(--muted); border:1px solid rgba(255,255,255,0.03)}
    footer{margin-top:12px; color:var(--muted); font-size:13px}
    @media (max-width:800px){
      .panels{grid-template-columns:1fr}
      .scoreBox{flex-direction:column; align-items:stretch}
      .score{width:100%; height:160px}
    }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card" id="resultCard" aria-live="polite">
      <header>
        <h2>Your IQ Results</h2>
        <div id="dateText" style="color:var(--muted);font-size:13px"></div>
      </header>
 
      <div class="scoreBox">
        <div class="score">
          <div style="font-size:13px;color:var(--muted)">Estimated IQ</div>
          <div class="num" id="iqNum">--</div>
          <div style="font-size:13px;color:var(--muted); margin-top:6px" id="iqLabel">Loading...</div>
        </div>
 
        <div class="panels" id="panels">
          <!-- panels filled by JS -->
        </div>
      </div>
 
      <div class="recommend" id="recommend">
        <!-- personalized feedback -->
      </div>
 
      <div class="actions">
        <button class="btn primary" id="retakeBtn">Retake Test</button>
        <button class="btn ghost" id="shareBtn">Share (copy)</button>
        <button class="btn ghost" id="downloadBtn">Download Report (JSON)</button>
      </div>
 
      <footer>Results are stored locally on your device only. You may retake or clear them anytime.</footer>
    </div>
  </div>
 
  <script>
    const raw = localStorage.getItem('iq_result');
    if(!raw){
      if(confirm('No IQ result found. Take the test now?')){
        window.location.href = 'quiz.php';
      } else {
        document.getElementById('resultCard').innerHTML = '<div style="padding:18px;color:var(--muted)">No results available. Use the Start Test page to begin.</div>';
      }
      throw('no result');
    }
 
    const res = JSON.parse(raw);
    const d = new Date(res.date);
    document.getElementById('dateText').textContent = d.toLocaleString();
 
    const iq = res.estimatedIQ;
    document.getElementById('iqNum').textContent = iq;
 
    // label: approximate classification
    let label = '';
    if(iq >= 125) label = 'Above average — Strong reasoning';
    else if(iq >= 100) label = 'Average — Solid skills';
    else if(iq >= 85) label = 'Below average — Some gaps';
    else label = 'Consider practice — Opportunity to improve';
 
    document.getElementById('iqLabel').textContent = label;
 
    // panels
    const panelsEl = document.getElementById('panels');
    Object.keys(res.breakdown).forEach(area => {
      const val = res.breakdown[area];
      const max = res.counts[area];
      const pct = Math.round((val / max) * 100);
      const panel = document.createElement('div');
      panel.className = 'panel';
      panel.innerHTML = `<h4>${area}</h4>
        <div class="val">${val} / ${max}</div>
        <div style="font-size:13px;color:var(--muted);margin-top:8px">${pct}% correct</div>`;
      panelsEl.appendChild(panel);
    });
 
    // Recommendations
    const rec = document.getElementById('recommend');
    let html = `<strong style="display:block;margin-bottom:8px">Feedback & next steps</strong>`;
    Object.keys(res.breakdown).forEach(area => {
      const val = res.breakdown[area];
      const max = res.counts[area];
      const pct = Math.round((val / max) * 100);
      if(pct >= 80){
        html += `<div style="margin-top:8px"><strong>${area}: Strength</strong>
                 <div style="color:var(--muted);margin-top:6px">Great performance in ${area.toLowerCase()}. Continue practising challenging puzzles to maintain sharpness.</div></div>`;
      } else if(pct >= 50){
        html += `<div style="margin-top:8px"><strong>${area}: Developing</strong>
                 <div style="color:var(--muted);margin-top:6px">Solid foundation in ${area.toLowerCase()}. Target focused exercises (10–15 mins daily) to boost accuracy and speed.</div></div>`;
      } else {
        html += `<div style="margin-top:8px"><strong>${area}: Needs practice</strong>
                 <div style="color:var(--muted);margin-top:6px">Work on basics in ${area.toLowerCase()}: use step-by-step strategies, timed practice, and short puzzles to build confidence.</div></div>`;
      }
 
      // concrete tips
      if(area === 'Logical'){
        html += `<div style="color:var(--muted);font-size:13px;margin-top:6px">Try: Logic puzzles, syllogisms, and practicing explanation of answers aloud.</div>`;
      } else if(area === 'Numerical'){
        html += `<div style="color:var(--muted);font-size:13px;margin-top:6px">Try: Mental arithmetic drills, number series practice, and breaking problems into small steps.</div>`;
      } else if(area === 'Pattern'){
        html += `<div style="color:var(--muted);font-size:13px;margin-top:6px">Try: Sequence puzzles, matrix pattern exercises, and visual pattern recognition drills.</div>`;
      }
    });
 
    html += `<div style="margin-top:12px;border-top:1px dashed rgba(255,255,255,0.03);padding-top:10px;color:var(--muted)">
             Overall raw score: ${res.correct} / ${res.maxTotal} (${res.percent}%). Estimated IQ: ${iq}. This is only an estimate — for formal assessment consult a licensed psychologist.</div>`;
 
    rec.innerHTML = html;
 
    // actions
    document.getElementById('retakeBtn').addEventListener('click', () => {
      if(confirm('Retake the IQ test? This will clear your stored result.')){
        localStorage.removeItem('iq_result');
        localStorage.removeItem('iq_answers');
        window.location.href = 'quiz.php';
      }
    });
 
    document.getElementById('shareBtn').addEventListener('click', () => {
      const shareText = `IQ Estimate: ${iq} — ${label}. Score ${res.correct}/${res.maxTotal} (${res.percent}%).`;
      navigator.clipboard.writeText(shareText).then(() => {
        alert('Result copied to clipboard. Paste anywhere to share.');
      }, () => {
        prompt('Copy this text to share:', shareText);
      });
    });
 
    document.getElementById('downloadBtn').addEventListener('click', () => {
      const dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(res, null, 2));
      const dl = document.createElement('a');
      dl.setAttribute('href', dataStr);
      dl.setAttribute('download', 'iq-result.json');
      document.body.appendChild(dl); dl.click(); dl.remove();
    });
 
    // small celebration for high IQ (visual)
    if(iq >= 125){
      setTimeout(()=> {
        for(let i=0;i<12;i++){
          const e = document.createElement('div');
          e.textContent = '✨';
          e.style.position='fixed';
          e.style.left = (10 + Math.random()*80)+'%';
          e.style.top = (10 + Math.random()*40)+'%';
          e.style.fontSize = (14 + Math.random()*36)+'px';
          e.style.opacity='0.95';
          e.style.transition='top 1.6s ease, opacity 1.6s ease';
          document.body.appendChild(e);
          setTimeout(()=> { e.style.top = (80 + Math.random()*10)+'%'; e.style.opacity='0'; }, 80);
          setTimeout(()=> e.remove(), 1800);
        }
      },400);
    }
 
  </script>
</body>
</html>
