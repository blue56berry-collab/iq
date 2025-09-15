<?php
// quiz.php
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>IQ Test — Quiz</title>
  <style>
    /* Internal CSS — sharp readable test UI */
    :root{
      --bg:#061322; --card:#071827; --accent:#ffd166; --muted:#9fb3c6;
      --radius:14px; --ff: Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial;
    }
    *{box-sizing:border-box}
    body{margin:0; font-family:var(--ff); min-height:100vh; background:linear-gradient(180deg,#051227,#071827); color:#f3f9fb; display:flex; align-items:center; justify-content:center; padding:20px}
    .wrap{width:100%; max-width:980px}
    .card{background: linear-gradient(180deg, rgba(255,255,255,0.01), rgba(255,255,255,0.005));
      border-radius:18px; padding:18px; border:1px solid rgba(255,255,255,0.03); box-shadow:0 12px 36px rgba(0,0,0,0.6)}
    header{display:flex; align-items:center; justify-content:space-between; margin-bottom:12px}
    header h2{margin:0; font-size:20px}
    .progress{font-size:13px; color:var(--muted)}
 
    .question{padding:14px; border-radius:12px; background:rgba(255,255,255,0.01); border:1px solid rgba(255,255,255,0.02); margin-bottom:14px}
    .qtext{font-weight:700; margin-bottom:10px}
    .opts{display:grid; gap:10px}
    .opt{display:flex; gap:12px; align-items:center; padding:10px; border-radius:10px; cursor:pointer; user-select:none;
      background:linear-gradient(180deg, rgba(255,255,255,0.004), transparent); border:1px solid rgba(255,255,255,0.02)}
    .opt.selected{box-shadow: 0 12px 30px rgba(0,0,0,0.6); border-color: rgba(255,209,102,0.12)}
    .nav{display:flex; gap:10px; justify-content:space-between; align-items:center}
    .btn{padding:10px 14px; border-radius:10px; border:0; cursor:pointer; font-weight:700}
    .btn.primary{background:linear-gradient(90deg,var(--accent), #ff9f1c); color:#06111a}
    .btn.ghost{background:transparent; color:var(--muted)}
    .summary{color:var(--muted); font-size:14px; margin-top:8px}
    footer{margin-top:14px; text-align:center; color:var(--muted); font-size:13px}
 
    @media (max-width:700px){
      header h2{font-size:16px}
    }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card" role="application" aria-labelledby="testTitle">
      <header>
        <h2 id="testTitle">IQ Test — Question</h2>
        <div class="progress" id="progress">Question 1 / 20</div>
      </header>
 
      <main id="quizArea">
        <!-- loaded by JS -->
      </main>
 
      <div class="nav">
        <div>
          <button class="btn ghost" id="prevBtn">◀ Previous</button>
        </div>
        <div style="display:flex; gap:10px;">
          <button class="btn ghost" id="saveBtn">Save</button>
          <button class="btn primary" id="nextBtn">Next ▶</button>
        </div>
      </div>
 
      <div class="summary" id="summaryText">Hint: Work calmly. Use scratch paper if needed for number/pattern questions.</div>
    </div>
 
    <footer>Answers stay on your device. No login required.</footer>
  </div>
 
  <script>
    // ====== QUESTION BANK (20) ======
    // Each question has options and 'a' = index of correct option (0-based)
    const QUESTIONS = [
      // Logical reasoning
      { id:1, area:'Logical', q:"If all Bloops are Razzies and some Razzies are Lups, which statement must be true?", opts:[
        "All Lups are Bloops",
        "Some Bloops may be Lups",
        "No Bloops are Lups",
        "All Razzies are Bloops"
      ], a:1},
      { id:2, area:'Logical', q:"Find the next in sequence: 2 → 6 → 12 → 20 → ?", opts:["30","24","28","26"], a:3},
      { id:3, area:'Logical', q:"If the day after tomorrow is two days before Thursday, what day is today?", opts:["Sunday","Monday","Tuesday","Wednesday"], a:2},
      { id:4, area:'Logical', q:"Which statement contradicts: 'Every A is B'?", opts:["Some B are not A","No A is B","All B are A","Some B are A"], a:1},
      { id:5, area:'Logical', q:"Choose the odd one out: Triangle, Square, Cube, Rectangle", opts:["Triangle","Square","Cube","Rectangle"], a:2},
 
      // Numerical ability
      { id:6, area:'Numerical', q:"What is 15% of 240?", opts:["36","34","30","40"], a:0},
      { id:7, area:'Numerical', q:"If 3x + 5 = 20, x = ?", opts:["3","5","6","4"], a:3},
      { id:8, area:'Numerical', q:"Which number is largest: 2^5, 5^2, 3^3?", opts:["2^5","5^2","3^3","They are equal"], a:0},
      { id:9, area:'Numerical', q:"A train travels 150 km in 2.5 hours. Its speed (km/h) is:", opts:["60","55","50","75"], a:3},
      { id:10, area:'Numerical', q:"Find x: 12 * x = 3/4 of 96", opts:["6","8","7","9"], a:1},
 
      // Pattern recognition / spatial
      { id:11, area:'Pattern', q:"Find next figure in numeric pattern: 1, 4, 9, 16, ?", opts:["25","20","30","24"], a:0},
      { id:12, area:'Pattern', q:"Which comes next: A, C, F, J, ?", opts:["O","P","N","M"], a:0},
      { id:13, area:'Pattern', q:"If the shape sequence adds one side each step: triangle, square, pentagon, next is", opts:["Hexagon","Heptagon","Octagon","Nonagon"], a:0},
      { id:14, area:'Pattern', q:"Complete the series: 2, 3, 5, 8, 13, ?", opts:["20","21","19","18"], a:1},
      { id:15, area:'Pattern', q:"Which item doesn't belong: circle, ellipse, triangle, oval", opts:["circle","ellipse","triangle","oval"], a:2},
 
      // Mixed tougher
      { id:16, area:'Logical', q:"If 5 machines take 5 minutes to make 5 widgets, how long would 100 machines take to make 100 widgets?", opts:["5 minutes","100 minutes","20 minutes","50 minutes"], a:0},
      { id:17, area:'Numerical', q:"What is the remainder when 2^10 is divided by 5?", opts:["4","2","1","0"], a:2},
      { id:18, area:'Pattern', q:"Which completes: 81, 27, 9, 3, ?", opts:["1","0","-1","6"], a:0},
      { id:19, area:'Numerical', q:"If the average of 5 numbers is 12 and one number 7 is replaced by 17, new average is:", opts:["12.8","13","12.5","13.2"], a:0},
      { id:20, area:'Logical', q:"Select statement logically equivalent to 'If P then Q' :", opts:["If not P then not Q","If Q then P","If not Q then not P","If P then not Q"], a:2}
    ];
 
    // ====== state ======
    let current = 0;
    const answers = new Array(QUESTIONS.length).fill(null); // store chosen index
 
    const quizArea = document.getElementById('quizArea');
    const progress = document.getElementById('progress');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const saveBtn = document.getElementById('saveBtn');
 
    function renderQuestion(idx){
      const q = QUESTIONS[idx];
      progress.textContent = `Question ${idx+1} / ${QUESTIONS.length}`;
 
      let html = `<div class="question" aria-live="polite">
        <div class="qtext">${q.q}</div>
        <div class="opts" role="list">`;
      q.opts.forEach((opt, i) => {
        const selected = (answers[idx] === i) ? 'selected' : '';
        html += `<div class="opt ${selected}" role="listitem" data-opt="${i}">
            <label style="display:flex;align-items:center;gap:12px;width:100%;cursor:pointer">
              <input type="radio" name="q${q.id}" ${answers[idx]===i ? 'checked':''} value="${i}" />
              <div style="flex:1"><div style="font-weight:600">${opt}</div></div>
            </label>
          </div>`;
      });
      html += `</div></div>`;
 
      quizArea.innerHTML = html;
 
      // selection listeners
      document.querySelectorAll('.opt').forEach((el, i) => {
        el.addEventListener('click', () => {
          answers[idx] = parseInt(el.dataset.opt, 10);
          document.querySelectorAll('.opt').forEach(x=> x.classList.remove('selected'));
          el.classList.add('selected');
          const input = el.querySelector('input');
          if(input) input.checked = true;
        });
      });
 
      // focus
      const firstInput = quizArea.querySelector('input');
      if(firstInput) firstInput.focus();
    }
 
    // navigation
    prevBtn.addEventListener('click', () => {
      if(current > 0){ current--; renderQuestion(current); }
    });
 
    nextBtn.addEventListener('click', () => {
      if(answers[current] === null){
        if(!confirm('You did not select an answer. Continue anyway?')) return;
      }
      if(current < QUESTIONS.length - 1){
        current++; renderQuestion(current);
      } else {
        submitAndRedirect();
      }
    });
 
    saveBtn.addEventListener('click', () => {
      localStorage.setItem('iq_answers', JSON.stringify(answers));
      alert('Progress saved locally on this device.');
    });
 
    (function tryLoad(){
      const saved = localStorage.getItem('iq_answers');
      if(saved){
        if(confirm('A saved attempt was found on this device. Load it?')){
          const arr = JSON.parse(saved);
          for(let i=0;i<Math.min(arr.length, answers.length);i++) answers[i]=arr[i];
        } else {
          localStorage.removeItem('iq_answers');
        }
      }
      renderQuestion(current);
    })();
 
    // submit -> scoring & redirect (JS only)
    function submitAndRedirect(){
      if(!confirm('Submit answers and view your estimated IQ?')) return;
 
      // compute raw score
      let correct = 0;
      const breakdown = { Logical:0, Numerical:0, Pattern:0 };
      const counts = { Logical:0, Numerical:0, Pattern:0 };
 
      QUESTIONS.forEach((q, i) => {
        counts[q.area] += 1;
        if(answers[i] === q.a) {
          correct += 1;
          breakdown[q.area] += 1;
        }
      });
 
      const maxTotal = QUESTIONS.length;
      const percent = Math.round((correct / maxTotal) * 100);
 
      // Map percent to estimated IQ range [70..130] linearly:
      // IQ = 70 + (percent * 0.6) --> percent 0 => 70, 100 => 130
      const estimatedIQ = Math.round(70 + (percent * 0.6));
 
      const result = {
        correct, maxTotal, percent, estimatedIQ, breakdown, counts, answers, date: new Date().toISOString()
      };
 
      localStorage.setItem('iq_result', JSON.stringify(result));
      localStorage.removeItem('iq_answers');
      window.location.href = 'result.php';
    }
 
    // keyboard nav
    document.addEventListener('keydown', (e) => {
      if(e.key === 'ArrowRight') nextBtn.click();
      if(e.key === 'ArrowLeft') prevBtn.click();
    });
 
  </script>
</body>
</html>
