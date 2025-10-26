<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forest Focus - شجرة واقعية</title>
    <style>
        :root{--bg:#0f1724;--card:#0b1220;--accent:#6bdc89;--muted:#94a3b8}
        *{box-sizing:border-box}
        body{margin:0;font-family:Inter, system-ui, Arial;background:linear-gradient(180deg,#071026 0%, #07132a 100%);color:#e6eef8;display:flex;align-items:center;justify-content:center;min-height:100vh;padding:20px}
        .app{width:100%;max-width:980px;background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));border-radius:12px;padding:20px;box-shadow:0 10px 30px rgba(2,6,23,0.6)}
        header{display:flex;gap:16px;align-items:center;justify-content:space-between}
        h1{font-size:20px;margin:0}
        .controls{display:flex;gap:8px;align-items:center}
        input[type=number]{width:80px;padding:8px;border-radius:8px;border:1px solid rgba(255,255,255,0.06);background:transparent;color:inherit}
        button{padding:10px 14px;border-radius:10px;border:none;background:var(--accent);color:#042022;font-weight:600;cursor:pointer}
        main{display:grid;grid-template-columns:1fr 340px;gap:20px;margin-top:18px}
        .panel{background:rgba(255,255,255,0.02);padding:16px;border-radius:12px}
        .timer{display:flex;flex-direction:column;align-items:center;gap:12px}
        .time{font-size:56px;font-weight:700}
        .tree-stage{width:260px;height:260px;display:flex;align-items:flex-end;justify-content:center;position:relative}

        /* تصميم الإناء */
        .pot{width:110px;height:70px;background:linear-gradient(to bottom, #8b4513, #6b3d2b, #5a3527);border-radius:8px 8px 6px 6px;position:absolute;bottom:0;box-shadow:0 4px 8px rgba(0,0,0,0.3), inset 0 2px 4px rgba(255,255,255,0.1)}
        .pot::before{content:'';position:absolute;top:5px;left:5px;right:5px;height:10px;background:linear-gradient(to bottom, rgba(255,255,255,0.1), transparent);border-radius:4px 4px 0 0}

        /* Tree Animation Styles */
        .tree-stage {
            position: relative;
            background: linear-gradient(to bottom, #87CEEB 0%, #98FB98 100%);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: inset 0 0 50px rgba(0,0,0,0.1);
        }

        .ground {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: linear-gradient(to bottom, #8B4513 0%, #654321 100%);
            border-radius: 0 0 20px 20px;
        }

        .trunk {
            position: absolute;
            bottom: 60px;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 0;
            background: linear-gradient(to right, #8B4513 0%, #A0522D 50%, #654321 100%);
            border-radius: 10px 10px 0 0;
            transition: height 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: inset -3px 0 5px rgba(0,0,0,0.3);
            z-index: 2;
        }

        .leaves {
            position: absolute;
            bottom: 60px;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: all 1s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 3;
        }

        .leaf-layer {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, #90EE90, #228B22);
            opacity: 0;
            transform: scale(0);
            transition: all 1.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .leaf-layer.visible {
            opacity: 1;
            transform: scale(1);
        }

        .leaf-layer:nth-child(1) {
            width: 80px;
            height: 60px;
            bottom: 80px;
            left: -40px;
            transition-delay: 0.2s;
        }

        .leaf-layer:nth-child(2) {
            width: 100px;
            height: 75px;
            bottom: 60px;
            left: -50px;
            transition-delay: 0.5s;
        }

        .leaf-layer:nth-child(3) {
            width: 120px;
            height: 90px;
            bottom: 40px;
            left: -60px;
            transition-delay: 0.8s;
        }

        .seedling {
            position: absolute;
            bottom: 60px;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: opacity 0.6s ease;
            z-index: 1;
        }

        .seedling-leaf {
            width: 15px;
            height: 8px;
            background: linear-gradient(45deg, #90EE90, #32CD32);
            border-radius: 50% 10px 50% 10px;
            position: absolute;
            bottom: 5px;
        }

        .seedling-leaf:nth-child(1) {
            left: -8px;
            transform: rotate(-30deg);
        }

        .seedling-leaf:nth-child(2) {
            right: -8px;
            transform: rotate(30deg);
        }

        .dead-tree {
            position: absolute;
            bottom: 60px;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: opacity 1s ease;
            z-index: 4;
        }

        .dead-trunk {
            width: 20px;
            height: 100px;
            background: linear-gradient(to right, #654321, #8B4513);
            border-radius: 10px 10px 0 0;
            position: relative;
        }

        .dead-branch {
            position: absolute;
            width: 25px;
            height: 4px;
            background: #654321;
            border-radius: 2px;
        }

        .dead-branch:nth-child(1) {
            top: 20px;
            right: -15px;
            transform: rotate(30deg);
        }

        .dead-branch:nth-child(2) {
            top: 40px;
            left: -15px;
            transform: rotate(-30deg);
        }

        .dead-branch:nth-child(3) {
            top: 60px;
            right: -12px;
            transform: rotate(45deg);
        }

        .tree-stage.dead .dead-tree {
            opacity: 1;
        }

        .tree-stage.dead .trunk,
        .tree-stage.dead .leaves,
        .tree-stage.dead .seedling {
            opacity: 0;
        }

        /* Floating particles */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 50%;
            animation: float 6s infinite linear;
        }

        @keyframes float {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-10px) rotate(360deg);
                opacity: 0;
            }
        }

        /* Progress bar */
        .progress-container {
            position: absolute;
            bottom: 10px;
            left: 10px;
            right: 10px;
            height: 6px;
            background: rgba(255,255,255,0.2);
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #32CD32, #90EE90);
            border-radius: 3px;
            transition: width 0.3s ease;
        }

        /* Stats styles */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 20px;
            width: 100%;
        }

        .stat-card {
            background: rgba(255,255,255,0.03);
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.05);
        }

        .stat-card .label {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 4px;
        }

        .stat-card .value {
            font-size: 18px;
            font-weight: bold;
            color: var(--accent);
        }

        /* Tabs styles */
        .tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 12px;
        }

        .tab-btn {
            padding: 6px 12px;
            border: 1px solid rgba(255,255,255,0.1);
            background: transparent;
            color: var(--muted);
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s ease;
        }

        .tab-btn.active {
            background: var(--accent);
            color: #042022;
            border-color: var(--accent);
        }

        .hist-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 6px;
            border-bottom: 1px dashed rgba(255,255,255,0.03);
            font-size: 13px;
            color: var(--muted);
        }

        footer {
            margin-top: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            color: var(--muted);
        }

        .logout-btn {
            background: transparent !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            color: var(--muted) !important;
        }

        @media (max-width:900px){
            main{grid-template-columns:1fr;}
            .tree-stage{width:200px;height:200px}
            header {
                flex-direction: column;
                gap: 12px;
            }
            .controls {
                flex-wrap: wrap;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="app" role="application">
        <header>
            <h1>🌳 Forest Focus — غابة التركيز</h1>
            <div class="controls">
                <label for="minutes">دقايق:</label>
                <input id="minutes" type="number" min="1" max="180" value="25">
                <button id="startBtn">ابدأ التركيز</button>
                <button id="stopBtn" style="display:none;background:#ef4444;color:white">إيقاف</button>
                {{-- <form method="POST" action="{{ route('logout') }}" style="display:inline">
                    @csrf
                    <button type="submit" class="logout-btn">تسجيل خروج</button>
                </form> --}}
            </div>
        </header>

        <main>
            <section class="panel timer">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="label">اليوم</div>
                        <div class="value" id="todayTrees">0</div>
                    </div>
                    <div class="stat-card">
                        <div class="label">الستريك</div>
                        <div class="value" id="currentStreak">0</div>
                    </div>
                    <div class="stat-card">
                        <div class="label">الأسبوع</div>
                        <div class="value" id="weekTrees">0</div>
                    </div>
                    <div class="stat-card">
                        <div class="label">الشهر</div>
                        <div class="value" id="monthTrees">0</div>
                    </div>
                </div>

                <div class="time" id="timeDisplay">25:00</div>
                <div class="tree-stage" id="treeStage" aria-hidden="true">
                    <div class="particles" id="particles"></div>
                    <div class="ground"></div>

                    <div class="seedling" id="seedling">
                        <div class="seedling-leaf"></div>
                        <div class="seedling-leaf"></div>
                    </div>

                    <div class="trunk" id="trunk"></div>

                    <div class="leaves" id="leaves">
                        <div class="leaf-layer"></div>
                        <div class="leaf-layer"></div>
                        <div class="leaf-layer"></div>
                    </div>

                    <div class="dead-tree" id="deadTree">
                        <div class="dead-trunk">
                            <div class="dead-branch"></div>
                            <div class="dead-branch"></div>
                            <div class="dead-branch"></div>
                        </div>
                    </div>

                    <div class="progress-container">
                        <div class="progress-bar" id="progressBar"></div>
                    </div>
                </div>
                <div id="message" style="color:var(--muted);text-align:center;margin-top:15px">🌱 جاهزة لزراعة شجرة جديدة؟</div>
            </section>

            <aside class="panel">
                <h3 style="margin-top:0">🏆 سجل الإنجازات</h3>
                <div class="tabs">
                    <button class="tab-btn active" data-period="day">اليوم</button>
                    <button class="tab-btn" data-period="week">الأسبوع</button>
                    <button class="tab-btn" data-period="month">الشهر</button>
                    <button class="tab-btn" data-period="year">السنة</button>
                </div>
                <div class="history" id="history"></div>
                <footer>
                    <div>🌳 الأشجار: <span id="treesCount">0</span></div>
                    <div>⏱️ الوقت: <span id="totalMinutes">0</span> د</div>
                </footer>
            </aside>
        </main>
    </div>

    <script>
        // إضافة CSRF token لجميع طلبات AJAX
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Elements
        const minutesInput = document.getElementById('minutes');
        const startBtn = document.getElementById('startBtn');
        const stopBtn = document.getElementById('stopBtn');
        const timeDisplay = document.getElementById('timeDisplay');
        const treeStage = document.getElementById('treeStage');
        const message = document.getElementById('message');
        const historyEl = document.getElementById('history');
        const treesCount = document.getElementById('treesCount');
        const totalMinutesEl = document.getElementById('totalMinutes');
        const todayTreesEl = document.getElementById('todayTrees');
        const weekTreesEl = document.getElementById('weekTrees');
        const monthTreesEl = document.getElementById('monthTrees');
        const currentStreakEl = document.getElementById('currentStreak');
        const tabButtons = document.querySelectorAll('.tab-btn');

        // State
        let totalSeconds = 25*60;
        let remaining = 0;
        let timerInterval = null;
        let running = false;
        let startTime = null;
        let lostFocus = false;
        let currentPeriod = 'day';

        // تحميل الإحصائيات
        async function loadStats() {
            try {
                const response = await fetch('/dashboard/stats');
                const data = await response.json();

                todayTreesEl.textContent = data.trees.today;
                weekTreesEl.textContent = data.trees.week;
                monthTreesEl.textContent = data.trees.month;
                currentStreakEl.textContent = data.streak.current;
                treesCount.textContent = data.trees.all_time;
                totalMinutesEl.textContent = data.total_minutes;
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }

        // تحميل السجل حسب الفترة
        async function loadHistory(period = 'day') {
            try {
                const response = await fetch(`/sessions?period=${period}`);
                const data = await response.json();

                historyEl.innerHTML = '';

                if (data.sessions.length === 0) {
                    const emptyMsg = document.createElement('div');
                    emptyMsg.className = 'hist-item';
                    emptyMsg.textContent = 'لا توجد جلسات في هذه الفترة';
                    emptyMsg.style.justifyContent = 'center';
                    emptyMsg.style.color = 'var(--muted)';
                    historyEl.appendChild(emptyMsg);
                } else {
                    data.sessions.forEach(session => {
                        const item = document.createElement('div');
                        item.className = 'hist-item';
                        const left = document.createElement('div');
                        left.textContent = '🌳 ' + session.duration + ' دقيقة';
                        const right = document.createElement('div');
                        const date = new Date(session.session_date);
                        right.textContent = date.toLocaleDateString('ar-EG');
                        item.appendChild(left);
                        item.appendChild(right);
                        historyEl.appendChild(item);
                    });
                }

                treesCount.textContent = data.total_trees;
                totalMinutesEl.textContent = data.total_minutes;
            } catch (error) {
                console.error('Error loading history:', error);
            }
        }

        // حفظ الجلسة
        async function saveSession(sessionData) {
            try {
                const response = await fetch('/sessions', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(sessionData)
                });

                if (response.ok) {
                    // تحديث الإحصائيات والسجل
                    loadStats();
                    loadHistory(currentPeriod);
                }
            } catch (error) {
                console.error('Error saving session:', error);
            }
        }

        // Particle system
        function createParticle() {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 6 + 's';
            particle.style.animationDuration = (4 + Math.random() * 4) + 's';
            particles.appendChild(particle);

            setTimeout(() => {
                if (particle.parentNode) {
                    particle.parentNode.removeChild(particle);
                }
            }, 8000);
        }

        function startParticles() {
            setInterval(() => {
                if (running && Math.random() < 0.3) {
                    createParticle();
                }
            }, 1000);
        }

        // Initialize
        function setDisplay(seconds){
            const m = Math.floor(seconds/60);
            const s = seconds%60;
            timeDisplay.textContent = `${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
        }

        function resetTree() {
            treeStage.classList.remove('dead');
            trunk.style.height = '0px';
            leaves.style.opacity = '0';
            seedling.style.opacity = '0';
            progressBar.style.width = '0%';

            const leafLayers = leaves.querySelectorAll('.leaf-layer');
            leafLayers.forEach(layer => {
                layer.classList.remove('visible');
            });
        }

        function updateTreeStage(progressPercent, dead=false){
            if(dead) {
                treeStage.classList.add('dead');
                return;
            }

            treeStage.classList.remove('dead');
            progressBar.style.width = progressPercent + '%';

            const leafLayers = leaves.querySelectorAll('.leaf-layer');

            // مرحلة البذرة (0-20%)
            if(progressPercent <= 20) {
                seedling.style.opacity = progressPercent / 20;
                trunk.style.height = '0px';
                leaves.style.opacity = '0';
            }
            // مرحلة نمو الجذع (20-40%)
            else if(progressPercent <= 40) {
                const p = (progressPercent - 20) / 20;
                seedling.style.opacity = Math.max(0, 1 - p);
                trunk.style.height = (p * 80) + 'px';
                leaves.style.opacity = '0';
            }
            // مرحلة الأوراق الأولى (40-60%)
            else if(progressPercent <= 60) {
                const p = (progressPercent - 40) / 20;
                seedling.style.opacity = '0';
                trunk.style.height = '80px';
                leaves.style.opacity = p;
                leafLayers[0].classList.add('visible');
            }
            // مرحلة الأوراق الثانية (60-80%)
            else if(progressPercent <= 80) {
                seedling.style.opacity = '0';
                trunk.style.height = '100px';
                leaves.style.opacity = '1';
                leafLayers[0].classList.add('visible');
                leafLayers[1].classList.add('visible');
            }
            // مرحلة الشجرة الكاملة (80-100%)
            else {
                seedling.style.opacity = '0';
                trunk.style.height = '120px';
                leaves.style.opacity = '1';
                leafLayers.forEach(layer => {
                    layer.classList.add('visible');
                });
            }
        }

        function startTimer(){
            if(running) return;
            const mins = Number(minutesInput.value) || 25;
            totalSeconds = mins*60;
            remaining = totalSeconds;
            startTime = Date.now();
            lostFocus = false;
            running = true;

            startBtn.style.display='none';
            stopBtn.style.display='inline-block';
            message.textContent='🎯 ركزي الآن... شجرتك تنمو!';

            resetTree();
            setDisplay(remaining);

            document.addEventListener('visibilitychange', onVisibilityChange);

            timerInterval = setInterval(()=>{
                const elapsed = Math.floor((Date.now()-startTime)/1000);
                remaining = Math.max(0, totalSeconds - elapsed);
                setDisplay(remaining);
                const progress = Math.min(100, (elapsed/totalSeconds)*100);
                updateTreeStage(progress, false);

                if(remaining <= 0){
                    clearInterval(timerInterval);
                    running = false;
                    startBtn.style.display='inline-block';
                    stopBtn.style.display='none';
                    message.textContent='🎉 مبروك! شجرتك اكتملت بنجاح!';
                    updateTreeStage(100, false);
                    saveSession({
                        duration: Math.round(totalSeconds/60),
                        success: true
                    });
                    document.removeEventListener('visibilitychange', onVisibilityChange);

                    // Celebration effect
                    for(let i = 0; i < 10; i++) {
                        setTimeout(() => createParticle(), i * 200);
                    }
                }
            }, 100);
        }

        function stopTimer(failed=false){
            if(!running) return;
            clearInterval(timerInterval);
            running = false;
            startBtn.style.display='inline-block';
            stopBtn.style.display='none';
            document.removeEventListener('visibilitychange', onVisibilityChange);

            if(failed){
                message.textContent='💀 الشجرة ماتت! فقدت التركيز...';
                updateTreeStage(0, true);
                saveSession({
                    duration: Math.round((totalSeconds-remaining)/60),
                    success: false
                });
            } else {
                message.textContent='⏸️ تم إيقاف المؤقت';
                resetTree();
                const elapsed = totalSeconds - remaining;
                if(elapsed > 60) {
                    saveSession({
                        duration: Math.round(elapsed/60),
                        success: false
                    });
                }
            }
        }

        function onVisibilityChange(){
            if(document.hidden && running){
                lostFocus = true;
                stopTimer(true);
            }
        }

        // إضافة event listeners للألسنة
        tabButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                tabButtons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                currentPeriod = btn.dataset.period;
                loadHistory(currentPeriod);
            });
        });

        // Event listeners
        startBtn.addEventListener('click', startTimer);
        stopBtn.addEventListener('click', () => stopTimer(false));

        minutesInput.addEventListener('input', () => {
            if(!running) {
                setDisplay(Number(minutesInput.value) * 60);
            }
        });

        // التهيئة
        document.addEventListener('DOMContentLoaded', () => {
            setDisplay(Number(minutesInput.value) * 60);
            loadStats();
            loadHistory('day');
            startParticles();
            resetTree();
        });
    </script>
</body>
</html>
