(function () {
  const C = window.QCHAT; if (!C) return;
  const $ = id => document.getElementById(id);
  const fab = $('chatFab'), box = $('chatBox'), body = $('chatBody'), form = $('chatForm'),
        input = $('chatInput'), intro = $('chatIntro'), nameEl = $('chatName'), contactEl = $('chatContact');
  const KEY = 'qreaget_chat_token';
  let token = null, lastId = 0, timer = null, seen = new Set(), open = false, started = false;
  try { token = localStorage.getItem(KEY); } catch (e) {}

  const headers = { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': C.csrf, 'X-Requested-With': 'XMLHttpRequest' };

  function esc(s) { const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }
  function add(m) {
    if (seen.has(m.id)) return; seen.add(m.id);
    lastId = Math.max(lastId, m.id);
    const el = document.createElement('div');
    el.className = 'msg ' + m.sender;
    el.innerHTML = (m.sender !== 'visitor' && m.author ? '<span class="who">' + esc(m.author) + '</span>' : '') + esc(m.body) + '<small>' + (m.time || '') + '</small>';
    body.appendChild(el); body.scrollTop = body.scrollHeight;
    if (!open && m.sender !== 'visitor') fab.classList.add('has-new');
  }
  async function startSession(force) {
    if (started && !force) return;
    const res = await fetch(C.start, { method: 'POST', headers, credentials: 'same-origin',
      body: JSON.stringify({ token, name: nameEl.value || null, contact: contactEl.value || null }) });
    if (!res.ok) return;
    const j = await res.json();
    token = j.token; started = true;
    try { localStorage.setItem(KEY, token); } catch (e) {}
    (j.messages || []).forEach(add);
    if (j.messages && j.messages.length) intro.style.display = 'none';
    schedule();
  }
  async function poll() {
    if (!token) return;
    try {
      const res = await fetch(C.poll + '?token=' + encodeURIComponent(token) + '&after=' + lastId, { headers, credentials: 'same-origin' });
      if (res.ok) (await res.json()).messages.forEach(add);
    } catch (e) {}
    schedule();
  }
  function schedule() { clearTimeout(timer); timer = setTimeout(poll, open ? 3500 : 15000); }

  fab.addEventListener('click', () => {
    open = !box.classList.contains('open');
    box.classList.toggle('open', open); fab.classList.remove('has-new');
    if (open) { startSession(); setTimeout(() => input.focus(), 50); schedule(); }
  });
  $('chatClose').addEventListener('click', () => { open = false; box.classList.remove('open'); schedule(); });
  input.addEventListener('keydown', e => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); form.requestSubmit(); } });
  form.addEventListener('submit', async e => {
    e.preventDefault();
    const text = input.value.trim(); if (!text) return;
    $('chatSend').disabled = true;
    try {
      if (!started || intro.style.display !== 'none') await startSession(true);
      const res = await fetch(C.send, { method: 'POST', headers, credentials: 'same-origin', body: JSON.stringify({ token, body: text }) });
      if (res.ok) {
        const j = await res.json(); add(j.message); (j.messages || []).forEach(add);
        input.value = ''; intro.style.display = 'none';
      }
    } finally { $('chatSend').disabled = false; input.focus(); }
  });
  // resume background polling for returning visitors
  if (token) { startSession(); }
})();
