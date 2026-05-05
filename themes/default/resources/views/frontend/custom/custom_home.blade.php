<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Platepilot — Crafted with Vision</title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,300;12..96,400;12..96,500;12..96,600;12..96,700;12..96,800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap" rel="stylesheet"/>
<style>
/* ── RESET & ROOT ── */
@media (max-width: 768px) {
  #hero,
  #cta,
  #why,
  #solutions,
  #showcase {
    overflow: hidden;
  }

  .hero-glow,
  .why-glow1,
  .why-glow2,
  .cta-orb1,
  .cta-orb2,
  .cta-noise {
    display: none;
  }

  .contact-form-card {
    max-width: 90%;
  }
  
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --o: #FF5A1F;
  --o-h: #E84E18;   /* hover: ~8% darker */
  --o-d: #CC4010;   /* deep/active: ~20% darker */
  --o-glow: #FFF2EC; /* very light warm tint */
  --o-mid: #FFCAAA;  /* mid tone for gradients/dividers */
  --o-soft: rgba(255, 90, 31, .08);  /* subtle backgrounds */
  --o-ring: rgba(255, 90, 31, .18);  /* focus rings/borders */
  --ink:#0A0A0A;
  --ink2:#1A1A1A;
  --ink3:#2D2D2D;
  --body:#4A4A4A;
  --muted:#7A7A7A;
  --muted2:#B0B0B0;
  --line:rgba(0,0,0,.07);
  --line2:rgba(0,0,0,.11);
  --surface:#FFFFFF;
  --surface2:#FAFAFA;
  --surface3:#F5F5F4;
  --r:10px;--rl:16px;--rxl:22px;--rxxl:30px;
  --sh:0 1px 3px rgba(0,0,0,.04),0 1px 2px rgba(0,0,0,.03);
  --sh2:0 4px 16px rgba(0,0,0,.06),0 1px 4px rgba(0,0,0,.03);
  --sh3:0 16px 48px rgba(0,0,0,.08),0 4px 16px rgba(0,0,0,.04);
  --sh4:0 32px 80px rgba(0,0,0,.10),0 8px 24px rgba(0,0,0,.05);
  --sho:0 8px 28px rgba(255,122,0,.20),0 2px 8px rgba(255,122,0,.10);
  --shoi:0 0 0 2px rgba(255,122,0,.15),0 4px 16px rgba(255,122,0,.12);
}
html{scroll-behavior:smooth;font-size:16px; overflow-x: hidden;}
body{font-family:'DM Sans',sans-serif;background:var(--surface);color:var(--ink);overflow-x:hidden;-webkit-font-smoothing:antialiased;text-rendering:optimizeLegibility; max-width: 100%;}
h1,h2,h3,h4,h5{font-family:'Bricolage Grotesque',sans-serif;line-height:1.1;letter-spacing:-0.025em;color:var(--ink)}
p{line-height:1.7;color:var(--body)}
a{text-decoration:none;color:inherit}
img{display:block;max-width:100%}
button{cursor:pointer;font-family:inherit;border:none}
::-webkit-scrollbar{width:4px}
::-webkit-scrollbar-track{background:transparent}
::-webkit-scrollbar-thumb{background:var(--o-mid);border-radius:99px}
::selection{background:rgba(255,122,0,.15);color:var(--ink)}

/* ── LAYOUT UTILS ── */
.container{max-width:1120px;margin:0 auto;padding:0 32px}
.section-gap{padding:120px 0}
.content-gap{margin-bottom:72px}

/* ── TYPOGRAPHY ── */
.eyebrow{display:inline-flex;align-items:center;gap:7px;font-family:'DM Sans',sans-serif;font-size:11.5px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:var(--o);background:var(--o-glow);border:1px solid var(--o-mid);padding:5px 12px;border-radius:99px;margin-bottom:20px}
.eyebrow-dot{width:5px;height:5px;border-radius:50%;background:var(--o);animation:pulse 2.2s ease-in-out infinite}
@keyframes pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.4;transform:scale(1.4)}}
.heading-xl{font-size:clamp(44px,6.5vw,82px);font-weight:800;line-height:1.05;letter-spacing:-.04em;color:var(--ink)}
.heading-lg{font-size:clamp(34px,4.5vw,56px);font-weight:700;letter-spacing:-.03em}
.heading-md{font-size:clamp(24px,3vw,36px);font-weight:700;letter-spacing:-.025em}
.subtext{font-size:clamp(15px,1.6vw,18px);color:var(--muted);font-weight:400;line-height:1.75;max-width:520px}

/* ── BUTTONS ── */
.btn{display:inline-flex;align-items:center;gap:8px;font-family:'DM Sans',sans-serif;font-size:14.5px;font-weight:600;padding:12px 24px;border-radius:var(--r);transition:all .18s ease;white-space:nowrap;cursor:pointer;border:none}
.btn-primary{background:var(--o);color:#fff;box-shadow:var(--sho)}
.btn-primary:hover{background:var(--o-h);transform:translateY(-1px);box-shadow:0 10px 32px rgba(255,122,0,.28)}
.btn-primary:active{transform:translateY(0)}
.btn-ghost{background:transparent;color:var(--ink2);border:1.5px solid var(--line2)}
.btn-ghost:hover{background:var(--surface2);border-color:var(--muted2)}
.btn-lg{padding:15px 30px;font-size:15.5px}
.btn-sm{padding:9px 18px;font-size:13px}
.btn svg{width:16px;height:16px;flex-shrink:0}

/* ── FADE ANIMATIONS ── */
.fade-up{opacity:0;transform:translateY(28px);transition:opacity .65s cubic-bezier(.25,.46,.45,.94),transform .65s cubic-bezier(.25,.46,.45,.94)}
.fade-up.visible{opacity:1;transform:none}
.d1{transition-delay:.08s}.d2{transition-delay:.16s}.d3{transition-delay:.24s}
.d4{transition-delay:.32s}.d5{transition-delay:.40s}.d6{transition-delay:.48s}

/* ══════════════════════════════════════
   NAV
══════════════════════════════════════ */
#nav{position:fixed;top:0;left:0;right:0;z-index:500;background:rgba(255,255,255,.88);backdrop-filter:blur(20px) saturate(160%);-webkit-backdrop-filter:blur(20px) saturate(160%);border-bottom:1px solid var(--line);transition:box-shadow .3s}
#nav.shadow{box-shadow:var(--sh2)}
.nav-inner{display:flex;align-items:center;justify-content:space-between;height:60px}
.nav-logo{font-family:'Bricolage Grotesque',sans-serif;font-size:21px;font-weight:800;letter-spacing:-.04em;color:var(--ink);display:flex;align-items:center;gap:9px}
.nav-logo-mark{width:30px;height:30px;border-radius:8px;background:var(--o);display:flex;align-items:center;justify-content:center}
.nav-logo-mark svg{width:16px;height:16px;color:#fff}
.nav-links{display:flex;align-items:center;list-style:none;gap:1px}
.nav-links a{font-size:13.5px;font-weight:500;color:var(--muted);padding:7px 13px;border-radius:8px;transition:all .15s}
.nav-links a:hover{color:var(--ink);background:var(--surface2)}
.nav-ctas{display:flex;align-items:center;gap:10px}
.nav-signin{font-size:13.5px;font-weight:500;color:var(--ink3);padding:7px 14px;border-radius:8px;transition:all .15s}
.nav-signin:hover{background:var(--surface2)}

/* ══════════════════════════════════════
   HERO
══════════════════════════════════════ */
#hero{padding:140px 0 100px;position:relative;overflow:hidden}
.hero-glow{position:absolute;top:-120px;left:50%;transform:translateX(-50%);width:900px;height:700px;background:radial-gradient(ellipse at 50% 30%,rgba(255,122,0,.10) 0%,transparent 65%);pointer-events:none;z-index:0}
.hero-grid{position:absolute;inset:0;background-image:linear-gradient(rgba(0,0,0,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(0,0,0,.04) 1px,transparent 1px);background-size:52px 52px;mask-image:radial-gradient(ellipse 80% 60% at 50% 0%,black 20%,transparent 70%);-webkit-mask-image:radial-gradient(ellipse 80% 60% at 50% 0%,black 20%,transparent 70%);z-index:0}
.hero-inner{position:relative;z-index:1;text-align:center;display:flex;flex-direction:column;align-items:center}
.hero-badge{margin-bottom:28px}
.hero-h1{margin-bottom:22px;max-width:780px}
.hero-h1 .italic{font-style:italic;color:var(--o)}
.hero-sub{margin:0 auto 40px;text-align:center}
.hero-actions{display:flex;align-items:center;gap:12px;flex-wrap:wrap;justify-content:center}
.hero-note{margin-top:18px;font-size:12.5px;color:var(--muted2)}

/* HERO DASHBOARD */
.hero-dash-wrap{margin-top:72px;position:relative;max-width:1000px;margin-left:auto;margin-right:auto}
.hero-dash-frame{background:var(--surface);border:1px solid var(--line2);border-radius:var(--rxxl);box-shadow:var(--sh4);overflow:hidden;position:relative}
.hdf-bar{background:var(--surface2);border-bottom:1px solid var(--line);padding:11px 18px;display:flex;align-items:center;gap:10px}
.hdf-dots{display:flex;gap:5px}
.hdf-dot{width:10px;height:10px;border-radius:50%}
.hdf-dot:nth-child(1){background:#FF5F57}
.hdf-dot:nth-child(2){background:#FEBC2E}
.hdf-dot:nth-child(3){background:#28C840}
.hdf-url{flex:1;text-align:center;font-size:11.5px;color:var(--muted);background:var(--surface3);border-radius:6px;padding:4px 0}
.hdf-body{display:grid;grid-template-columns:196px 1fr}
.hdf-sidebar{background:var(--surface2);border-right:1px solid var(--line);padding:18px 14px;display:flex;flex-direction:column;gap:2px}
.hdf-brand{display:flex;align-items:center;gap:8px;padding:8px 10px;margin-bottom:10px}
.hdf-brand-ico{width:26px;height:26px;border-radius:7px;background:var(--o);display:flex;align-items:center;justify-content:center;font-family:'Bricolage Grotesque',sans-serif;font-size:12px;font-weight:800;color:#fff}
.hdf-brand-name{font-family:'Bricolage Grotesque',sans-serif;font-size:14px;font-weight:700;color:var(--ink)}
.hdf-nav-label{font-size:9.5px;font-weight:700;color:var(--muted2);letter-spacing:.09em;text-transform:uppercase;padding:8px 10px 3px}
.hdf-nav-item{display:flex;align-items:center;gap:8px;padding:7px 10px;border-radius:7px;font-size:12px;font-weight:500;color:var(--body);cursor:pointer;transition:all .15s}
.hdf-nav-item.active{background:rgba(255,122,0,.09);color:var(--o)}
.hdf-nav-item svg,.hdf-nav-item .nav-ico{width:14px;height:14px;flex-shrink:0}
.hdf-main{padding:22px;background:var(--surface)}
.hdf-top-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px}
.hdf-page-title{font-family:'Bricolage Grotesque',sans-serif;font-size:18px;font-weight:700;color:var(--ink)}
.hdf-date{font-size:11.5px;color:var(--muted);background:var(--surface2);padding:5px 11px;border-radius:7px;border:1px solid var(--line)}
.hdf-kpi-row{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:14px}
.hdf-kpi{background:var(--surface);border:1px solid var(--line);border-radius:var(--r);padding:12px 14px}
.hdf-kpi-label{font-size:10px;font-weight:600;color:var(--muted);margin-bottom:4px;letter-spacing:.02em}
.hdf-kpi-val{font-family:'Bricolage Grotesque',sans-serif;font-size:22px;font-weight:700;color:var(--ink);line-height:1}
.hdf-kpi-delta{font-size:10px;font-weight:600;color:#16a34a;margin-top:3px}
.hdf-charts-row{display:grid;grid-template-columns:5fr 3fr;gap:10px}
.hdf-chart-card{background:var(--surface);border:1px solid var(--line);border-radius:var(--r);padding:13px 15px}
.hdf-chart-title{font-size:11px;font-weight:600;color:var(--ink3);margin-bottom:10px}
.mini-bars{display:flex;align-items:flex-end;gap:5px;height:72px}
.mb{flex:1;border-radius:3px 3px 0 0;background:var(--o-mid);transition:height .4s ease;transform-origin:bottom;animation:barGrow .8s ease forwards}
.mb.hi{background:var(--o)}
@keyframes barGrow{from{transform:scaleY(0)}to{transform:scaleY(1)}}
.mb-x{text-align:center;font-size:8.5px;color:var(--muted2);margin-top:4px;font-family:'DM Sans',sans-serif}
.orders-feed{display:flex;flex-direction:column;gap:6px}
.order-item{display:flex;align-items:center;gap:7px}
.order-avatar{width:26px;height:26px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:9.5px;font-weight:700;font-family:'Bricolage Grotesque',sans-serif;color:#fff;flex-shrink:0}
.order-info{flex:1}
.order-name{font-size:11px;font-weight:600;color:var(--ink)}
.order-detail{font-size:10px;color:var(--muted)}
.order-badge{font-size:9.5px;font-weight:700;padding:2px 8px;border-radius:99px}
.ob-green{background:rgba(22,163,74,.1);color:#16a34a}
.ob-orange{background:rgba(255,122,0,.1);color:var(--o)}
.ob-blue{background:rgba(59,130,246,.1);color:#3b82f6}
.hero-float{position:absolute;background:var(--surface);border:1px solid var(--line2);border-radius:var(--rl);padding:10px 14px;box-shadow:var(--sh3)}
.hf1{left:-52px;top:90px;animation:float1 5s ease-in-out infinite}
.hf2{right:-48px;top:60px;animation:float1 6.5s ease-in-out infinite;animation-delay:.8s}
.hf3{right:-44px;bottom:70px;animation:float1 5.5s ease-in-out infinite;animation-delay:1.4s}
@keyframes float1{0%,100%{transform:translateY(0)}50%{transform:translateY(-11px)}}
.hf-label{font-size:9px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.07em;margin-bottom:2px}
.hf-value{font-family:'Bricolage Grotesque',sans-serif;font-size:19px;font-weight:700;color:var(--ink);line-height:1}
.hf-sub{font-size:10px;font-weight:600;color:#16a34a;margin-top:2px}
.hf-pill{display:inline-flex;align-items:center;gap:4px;font-size:10.5px;font-weight:600;padding:3px 9px;border-radius:99px;background:rgba(255,122,0,.1);color:var(--o);margin-top:4px}
.hf-pill::before{content:'';width:5px;height:5px;border-radius:50%;background:var(--o);animation:pulse 1.5s infinite}

/* ══════════════════════════════════════
   TRUST STRIP
══════════════════════════════════════ */
#trust{border-top:1px solid var(--line);border-bottom:1px solid var(--line);padding:38px 0;background:var(--surface2)}
.trust-inner{text-align:center}
.trust-text{font-size:13px;color:var(--muted);margin-bottom:26px;font-weight:400}
.trust-logos{display:flex;align-items:center;justify-content:center;gap:48px;flex-wrap:wrap}
.trust-logo{font-family:'Bricolage Grotesque',sans-serif;font-size:18px;font-weight:700;color:var(--muted2);letter-spacing:-.02em;transition:color .2s;user-select:none}
.trust-logo:hover{color:var(--ink3)}

/* ══════════════════════════════════════
   SOLUTIONS
══════════════════════════════════════ */
#solutions{padding:120px 0}
.sol-grid{display:grid;grid-template-columns:1fr 1fr;gap:72px;align-items:center;margin-top:72px}
.sol-items{display:flex;flex-direction:column;gap:16px}
.sol-item{display:flex;gap:14px;align-items:flex-start;padding:18px;border:1px solid var(--line);border-radius:var(--rl);background:var(--surface);transition:all .22s}
.sol-item:hover{border-color:var(--o-mid);box-shadow:var(--sh2);transform:translateX(3px)}
.sol-icon{width:38px;height:38px;border-radius:10px;background:var(--o-glow);color:var(--o);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.sol-icon svg{width:18px;height:18px}
.sol-title{font-family:'Bricolage Grotesque',sans-serif;font-size:14.5px;font-weight:700;color:var(--ink);margin-bottom:4px}
.sol-desc{font-size:13px;color:var(--muted);line-height:1.6}
/* Phone mockup */
.phone-outer{width:270px;margin:0 auto;position:relative}
.phone-body{background:var(--ink);border-radius:36px;padding:12px;box-shadow:var(--sh4);animation:float1 6s ease-in-out infinite}
.phone-screen{background:#fff;border-radius:28px;overflow:hidden;min-height:480px}
.phone-header{background:var(--o);padding:18px 16px 14px;text-align:center}
.phone-restaurant{font-family:'Bricolage Grotesque',sans-serif;font-size:15px;font-weight:800;color:#fff;margin-bottom:2px}
.phone-delivery-tag{font-size:10px;color:rgba(255,255,255,.7);font-weight:500}
.phone-body-content{padding:12px}
.phone-section-label{font-size:9.5px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--muted);margin:8px 0 7px;font-family:'Bricolage Grotesque',sans-serif}
.phone-menu-items{display:flex;flex-direction:column;gap:6px}
.phone-menu-item{display:flex;align-items:center;gap:9px;background:var(--surface2);border-radius:9px;padding:9px 10px;border:1px solid var(--line)}
.pmi-emoji{font-size:18px;flex-shrink:0;width:30px;text-align:center}
.pmi-name{font-size:11px;font-weight:600;color:var(--ink);line-height:1.2}
.pmi-price{font-size:10px;color:var(--muted)}
.pmi-add{margin-left:auto;width:22px;height:22px;border-radius:6px;background:var(--o);display:flex;align-items:center;justify-content:center;color:#fff;font-size:14px;font-weight:700;cursor:pointer;flex-shrink:0}
.phone-cta{margin:12px;background:var(--o);border-radius:10px;padding:12px;text-align:center;font-family:'Bricolage Grotesque',sans-serif;font-size:13px;font-weight:800;color:#fff}
.phone-float-badge{position:absolute;top:-14px;right:-18px;background:var(--surface);border:1px solid var(--line2);border-radius:var(--r);padding:9px 13px;box-shadow:var(--sh3)}
.phone-float-badge2{position:absolute;bottom:-12px;left:-16px;background:var(--surface);border:1px solid var(--line2);border-radius:var(--r);padding:9px 13px;box-shadow:var(--sh3)}

/* ══════════════════════════════════════
   INTERACTIVE FEATURE STORY (STICKY)
══════════════════════════════════════ */

#features { padding: 120px 0; background: var(--ink); }
.features-header { text-align: center; margin-bottom: 72px; }
.features-header h2 {
  font-size: clamp(34px, 4vw, 52px); font-weight: 800; color: #fff; margin-bottom: 16px;
}
.features-header p { font-size: 18px; color: rgba(255,255,255,0.4); max-width: 500px; margin: 0 auto; }
.features-header .tag {
  display:inline-block; padding: 4px 14px; border-radius: 99px;
  background: rgba(255,122,0,0.12); color: var(--o); font-size: 12px;
  font-weight: 700; letter-spacing: .06em; text-transform: uppercase; margin-bottom: 16px;
}
.features-layout {
  display: grid; grid-template-columns: 340px 1fr; gap: 48px; align-items: start;
}
.features-nav { display: flex; flex-direction: column; gap: 4px; position: sticky; top: 100px; }
.feature-nav-item {
  display: flex; align-items: flex-start; gap: 14px;
  padding: 18px 20px; border-radius: var(--rl);
  cursor: pointer; transition: all 0.25s; border: 1.5px solid transparent;
  position: relative; overflow: hidden;
}
.feature-nav-item:hover { background: rgba(255,255,255,0.03); }
.feature-nav-item.active { background: rgba(255,122,0,0.08); border-color: rgba(255,122,0,0.2); }
.fnav-icon {
  width: 36px; height: 36px; border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
  font-size: 16px; flex-shrink: 0; transition: background 0.25s;
  background: rgba(255,255,255,0.05);
  color: #FF5A1F;
}
.feature-nav-item.active .fnav-icon { background: rgba(255,122,0,0.15); color: #fff;}
.fnav-title { font-size: 14px; font-weight: 700; color: rgba(255,255,255,0.5); transition: color 0.25s; margin-bottom: 3px; }
.feature-nav-item.active .fnav-title { color: #fff; }
.fnav-desc { font-size: 13px; color: rgba(255,255,255,0.28); line-height: 1.5; transition: color 0.25s; }
.feature-nav-item.active .fnav-desc { color: rgba(255,255,255,0.45); }
.fnav-indicator {
  position: absolute; left: 0; top: 50%; transform: translateY(-50%);
  width: 3px; height: 0; background: var(--o); border-radius: 0 2px 2px 0;
  transition: height 0.3s ease;
}
.feature-nav-item.active .fnav-indicator { height: 60%; }
.features-preview {
  background: rgba(255,255,255,0.03); border-radius: var(--rxl);
  border: 1px solid rgba(255,255,255,0.07); overflow: hidden;
  min-height: 480px; position: relative;
}
.feature-panel { display: none; padding: 36px; flex-direction: column; gap: 24px; animation: panelIn 0.35s ease; }
.feature-panel.active { display: flex; }
@keyframes panelIn {
  from { opacity: 0; transform: translateY(12px); }
  to { opacity: 1; transform: translateY(0); }
}
.panel-title {
  font-family: 'Bricolage Grotesque', sans-serif; font-size: 24px; font-weight: 800;
  color: #fff; margin-bottom: 8px;
}
.panel-desc { font-size: 15px; color: rgba(255,255,255,0.45); line-height: 1.7; margin-bottom: 20px; }
.panel-visual {
  background: rgba(255,255,255,0.04); border-radius: var(--rl);
  border: 1px solid rgba(255,255,255,0.06); padding: 28px;
  min-height: 280px; position: relative; overflow: hidden;
}

/* --- FEATURES UI ELEMENTS --- */
.delivery-stat-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px; }
.dv-card { background: rgba(255,255,255,0.05); padding: 14px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); }
.dv-card-lbl { font-size: 10px; color: rgba(255,255,255,0.4); text-transform: uppercase; font-weight: 700; margin-bottom: 4px; }
.dv-card-val { font-family: 'Bricolage Grotesque', sans-serif; font-size: 20px; color: #fff; font-weight: 700; }
.dv-card-sub { font-size: 10px; color: var(--muted); margin-top: 2px; }

.delivery-order-list { display: flex; flex-direction: column; gap: 8px; }
.dv-order { display: flex; align-items: center; gap: 10px; background: rgba(255,255,255,0.03); padding: 10px; border-radius: 10px; }
.dv-order-emoji { font-size: 16px; }
.dv-order-info { flex: 1; }
.dv-order-name { font-size: 12px; font-weight: 600; color: #fff; }
.dv-order-addr { font-size: 10px; color: var(--muted); }
.dv-order-eta { font-size: 11px; font-weight: 700; color: var(--o); }

.pay-methods { display: flex; gap: 8px; margin-bottom: 20px; }
.pay-method { background: #fff; color: #000; padding: 8px 12px; border-radius: 8px; font-weight: 700; font-size: 12px; }
.pay-tx { display: flex; justify-content: space-between; padding: 10px; background: rgba(255,255,255,0.05); border-radius: 8px; font-size: 12px; color: #fff; margin-bottom: 6px; }

.analytics-kpis { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
.akpi { text-align: center; }
.akpi-val { font-family: 'Bricolage Grotesque', sans-serif; font-size: 18px; color: #fff; font-weight: 700; }
.akpi-lbl { font-size: 10px; color: var(--muted); }

.promo-card-alt { background: var(--o-soft); border: 1px solid var(--o-ring); padding: 15px; border-radius: 12px; margin-bottom: 15px; }
.p-tag { font-size: 9px; font-weight: 800; color: var(--o); margin-bottom: 4px; }
.p-name { color: #fff; font-weight: 700; font-size: 14px; }
.p-stat { color: rgba(255,255,255,0.5); font-size: 11px; }
.p-bar { height: 6px; background: rgba(255,255,255,0.1); border-radius: 10px; flex: 1; overflow: hidden; }
.p-fill { height: 100%; background: var(--o); }
.promo-perf-row { display: flex; align-items: center; gap: 10px; }
.p-val { font-size: 11px; color: #fff; font-weight: 600; }

.branch-item-mini { display: flex; align-items: center; gap: 10px; background: rgba(255,255,255,0.03); padding: 12px; border-radius: 10px; margin-bottom: 8px; color: #fff; font-size: 13px; }
.b-dot { width: 8px; height: 8px; border-radius: 50%; }
.b-dot.online { background: #4ADE80; box-shadow: 0 0 8px #4ADE80; }
.b-dot.offline { background: #555; }
.branch-item-mini strong { margin-left: auto; }

.int-grid-mini { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
.int-box { background: rgba(255,255,255,0.05); height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; border: 1px solid rgba(255,255,255,0.1); }

.tmpl-row-mini { display: flex; gap: 12px; justify-content: center; padding-top: 20px; }
.tmpl-box-mini { width: 60px; height: 80px; background: #fff; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 24px; border: 3px solid transparent; }
.tmpl-box-mini.active { border-color: var(--o); transform: scale(1.1); }

/* ─────────────────────────────────────── INTEGRATIONS ─── */
  #integrations { padding: 120px 0; }
  .integrations-header { text-align: center; margin-bottom: 72px; }
  .integrations-header h2 { font-size: clamp(34px, 4vw, 50px); font-weight: 800; margin-bottom: 16px; }
  .integrations-header p { font-size: 18px; color: var(--gray-400); max-width: 480px; margin: 0 auto; }
  .integrations-categories { display: flex; gap: 8px; justify-content: center; margin-bottom: 40px; flex-wrap: wrap; }
  .int-cat {
    padding: 7px 16px; border-radius: 99px; font-size: 13px; font-weight: 600;
    background: var(--gray-100); color: var(--gray-400); cursor: pointer; transition: all 0.2s;
    border: 1.5px solid transparent;
  }
  .int-cat.active, .int-cat:hover { background: var(--orange-soft); color: var(--orange); border-color: rgba(255,122,0,0.2); }
  /* .integrations-grid { display: grid; grid-template-columns: repeat(6, 1fr); gap: 14px; } */
  .integrations-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, max-content));
    justify-content: center;
    gap: 14px;
  }
  .int-card {
    padding: 20px 16px; background: var(--white); border-radius: var(--radius-md);
    border: 1.5px solid var(--gray-200); text-align: center;
    transition: all 0.25s; cursor: pointer;
  }
  .int-card:hover { border-color: rgba(255,122,0,0.3); box-shadow: 0 4px 16px rgba(255,122,0,0.08); transform: translateY(-2px); }
  .int-icon { font-size: 28px; margin-bottom: 8px; }
  .int-name { font-size: 12px; font-weight: 700; color: var(--black); }
  .int-cat-label { font-size: 10px; color: var(--gray-400); margin-top: 3px; text-transform: uppercase; letter-spacing: 0.05em; }

/* #features{padding:120px 0;background:var(--surface2);border-top:1px solid var(--line);border-bottom:1px solid var(--line)}
.features-header{text-align:center;margin-bottom:72px}
.features-sticky-wrap{display:grid;grid-template-columns:340px 1fr;gap:56px;align-items:start}
.features-nav{position:sticky;top:100px}
.feat-nav-items{display:flex;flex-direction:column;gap:4px;margin-top:24px}
.feat-nav-item{padding:14px 16px;border-radius:var(--rl);cursor:pointer;border:1.5px solid transparent;transition:all .22s;display:flex;align-items:flex-start;gap:12px}
.feat-nav-item:hover{background:rgba(255,122,0,.05);border-color:var(--o-mid)}
.feat-nav-item.active{background:rgba(255,122,0,.07);border-color:var(--o-mid);box-shadow:var(--shoi)}
.feat-nav-icon{width:34px;height:34px;border-radius:9px;background:var(--o-glow);color:var(--o);display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:all .22s}
.feat-nav-item.active .feat-nav-icon{background:var(--o);color:#fff}
.feat-nav-icon svg{width:16px;height:16px}
.feat-nav-text-title{font-family:'Bricolage Grotesque',sans-serif;font-size:14px;font-weight:700;color:var(--ink);margin-bottom:3px;transition:color .15s}
.feat-nav-text-desc{font-size:12.5px;color:var(--muted);line-height:1.5}
.feat-preview-panel{position:sticky;top:100px;height:600px}
.feat-preview-card{background:var(--surface);border:1px solid var(--line2);border-radius:var(--rxl);box-shadow:var(--sh4);overflow:hidden;height:100%;position:relative}
.feat-panel{position:absolute;inset:0;display:flex;flex-direction:column;opacity:0;transform:translateY(12px);transition:opacity .3s ease,transform .3s ease;pointer-events:none}
.feat-panel.active{opacity:1;transform:none;pointer-events:auto} */

/* Panel: Delivery & Pickup */
/* .panel-delivery{padding:28px}
.panel-top-bar{background:var(--o);padding:14px 18px;margin:-28px -28px 22px;display:flex;align-items:center;justify-content:space-between}
.panel-top-bar-title{font-family:'Bricolage Grotesque',sans-serif;font-size:14px;font-weight:800;color:#fff}
.panel-live-badge{display:flex;align-items:center;gap:5px;font-size:11px;font-weight:600;color:rgba(255,255,255,.8)}
.panel-live-dot{width:6px;height:6px;border-radius:50%;background:#fff;animation:pulse 1.5s infinite}
.map-mock{background:var(--surface3);border-radius:var(--rl);height:160px;overflow:hidden;position:relative;margin-bottom:16px}
.map-grid{position:absolute;inset:0;background-image:linear-gradient(rgba(0,0,0,.06) 1px,transparent 1px),linear-gradient(90deg,rgba(0,0,0,.06) 1px,transparent 1px);background-size:30px 30px}
.map-pin{position:absolute;width:28px;height:28px;border-radius:50%;background:var(--o);display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;border:3px solid #fff;box-shadow:var(--sh2);animation:float1 3s ease-in-out infinite}
.map-pin1{left:30%;top:35%}
.map-pin2{left:60%;top:55%;animation-delay:.7s}
.delivery-orders-list{display:flex;flex-direction:column;gap:8px}
.do-item{display:flex;align-items:center;gap:11px;padding:10px 12px;background:var(--surface2);border-radius:var(--r);border:1px solid var(--line)}
.do-num{font-family:'Bricolage Grotesque',sans-serif;font-size:11px;font-weight:700;color:var(--muted);width:24px;flex-shrink:0}
.do-info{flex:1}
.do-name{font-size:12px;font-weight:600;color:var(--ink)}
.do-detail{font-size:11px;color:var(--muted)}
.do-status{font-size:10.5px;font-weight:700;padding:3px 9px;border-radius:99px} */

/* Panel: Payments */
/* .panel-payments{padding:28px}
.payment-cards{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px}
.payment-card{background:var(--surface2);border:1px solid var(--line);border-radius:var(--rl);padding:16px}
.payment-logo{font-family:'Bricolage Grotesque',sans-serif;font-size:13px;font-weight:800;margin-bottom:8px}
.payment-status{display:flex;align-items:center;gap:5px;font-size:10.5px;font-weight:600;color:#16a34a}
.payment-stat{font-family:'Bricolage Grotesque',sans-serif;font-size:22px;font-weight:800;color:var(--ink);margin-top:4px}
.payment-stat-label{font-size:10px;color:var(--muted)}
.payment-tx-list{display:flex;flex-direction:column;gap:7px}
.ptx{display:flex;align-items:center;gap:10px;padding:9px 12px;background:var(--surface2);border-radius:var(--r);border:1px solid var(--line)}
.ptx-icon{width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0}
.ptx-name{font-size:12px;font-weight:600;color:var(--ink);flex:1}
.ptx-method{font-size:10.5px;color:var(--muted)}
.ptx-amount{font-family:'Bricolage Grotesque',sans-serif;font-size:14px;font-weight:700;color:var(--ink)}
.ptx-amount.neg{color:#dc2626} */

/* Panel: Reports */
/* .panel-reports{padding:28px}
.reports-chart-wrap{background:var(--surface2);border:1px solid var(--line);border-radius:var(--rl);padding:16px;margin-bottom:14px}
.rcw-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px}
.rcw-title{font-family:'Bricolage Grotesque',sans-serif;font-size:13px;font-weight:700;color:var(--ink)}
.rcw-badge{font-size:10px;font-weight:700;padding:3px 9px;border-radius:99px;background:rgba(22,163,74,.1);color:#16a34a}
.big-bars{display:flex;align-items:flex-end;gap:7px;height:100px}
.bb{flex:1;border-radius:4px 4px 0 0;background:var(--o-mid);transform-origin:bottom;animation:barGrow .6s ease forwards}
.bb.hi{background:var(--o)}
.reports-metrics{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}
.rm-card{background:var(--surface2);border:1px solid var(--line);border-radius:var(--r);padding:12px}
.rm-val{font-family:'Bricolage Grotesque',sans-serif;font-size:20px;font-weight:700;color:var(--ink);line-height:1;margin-bottom:3px}
.rm-label{font-size:10.5px;color:var(--muted)} */

/* Panel: Promotions */
/* .panel-promotions{padding:28px}
.promo-cards{display:flex;flex-direction:column;gap:10px;margin-bottom:14px}
.promo-card{background:var(--surface2);border:1px solid var(--line);border-radius:var(--rl);padding:14px 16px}
.promo-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:10px}
.promo-title{font-family:'Bricolage Grotesque',sans-serif;font-size:13.5px;font-weight:700;color:var(--ink)}
.promo-status{font-size:10px;font-weight:700;padding:3px 9px;border-radius:99px}
.promo-bar-wrap{background:var(--surface3);border-radius:99px;height:7px;overflow:hidden}
.promo-bar{height:100%;border-radius:99px;background:var(--o);animation:progFill .9s ease forwards;transform-origin:left}
@keyframes progFill{from{transform:scaleX(0)}to{transform:scaleX(1)}}
.promo-stats{display:flex;gap:16px;margin-top:8px}
.promo-stat-lbl{font-size:10px;color:var(--muted)}
.promo-stat-val{font-family:'Bricolage Grotesque',sans-serif;font-size:13px;font-weight:700;color:var(--ink)} */

/* Panel: Multi-Branch */
/* .panel-branches{padding:28px}
.branch-list{display:flex;flex-direction:column;gap:9px;margin-bottom:16px}
.branch-row{display:flex;align-items:center;gap:12px;padding:12px 14px;background:var(--surface2);border-radius:var(--r);border:1.5px solid transparent;cursor:pointer;transition:all .18s}
.branch-row:hover,.branch-row.active{border-color:var(--o-mid);background:rgba(255,122,0,.04)}
.branch-indicator{width:10px;height:10px;border-radius:50%;background:var(--o-mid);flex-shrink:0}
.branch-row.active .branch-indicator{background:var(--o)}
.branch-name{font-family:'Bricolage Grotesque',sans-serif;font-size:13px;font-weight:700;color:var(--ink);flex:1}
.branch-orders{font-size:11.5px;color:var(--muted)}
.branch-rev{font-family:'Bricolage Grotesque',sans-serif;font-size:14px;font-weight:700;color:var(--ink)}
.branch-detail{background:var(--surface2);border:1px solid var(--line);border-radius:var(--rl);padding:14px 16px}
.bd-title{font-size:11px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.07em;margin-bottom:10px}
.bd-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:8px}
.bd-stat{text-align:center}
.bd-val{font-family:'Bricolage Grotesque',sans-serif;font-size:18px;font-weight:700;color:var(--ink)}
.bd-lbl{font-size:10px;color:var(--muted)} */

/* Panel: CRM */
/* .panel-crm{padding:28px}
.crm-integration-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px}
.crm-int-card{background:var(--surface2);border:1px solid var(--line);border-radius:var(--r);padding:12px 14px;display:flex;align-items:center;gap:10px}
.crm-int-ico{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0}
.crm-int-name{font-size:12.5px;font-weight:600;color:var(--ink)}
.crm-int-type{font-size:10.5px;color:var(--muted)}
.crm-connected{display:inline-flex;align-items:center;gap:4px;font-size:10px;font-weight:600;color:#16a34a;margin-top:2px}
.crm-connected::before{content:'';width:5px;height:5px;border-radius:50%;background:#16a34a}
.crm-activity{display:flex;flex-direction:column;gap:7px}
.crm-act{display:flex;align-items:center;gap:9px;padding:8px 10px;background:var(--surface2);border-radius:var(--r);border:1px solid var(--line)}
.crm-act-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0}
.crm-act-text{font-size:11.5px;color:var(--ink3);flex:1}
.crm-act-time{font-size:10.5px;color:var(--muted)} */

/* Panel: Templates */
/* .panel-templates{padding:28px}
.tmpl-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:12px}
.tmpl-card{border:2px solid var(--line);border-radius:var(--rl);overflow:hidden;cursor:pointer;transition:all .2s}
.tmpl-card:hover{border-color:var(--o-mid)}
.tmpl-card.active{border-color:var(--o);box-shadow:var(--shoi)}
.tmpl-topbar{height:20px;display:flex;align-items:center;gap:3px;padding:0 7px;border-bottom:1px solid var(--line)}
.tmpl-topbar-dot{width:5px;height:5px;border-radius:50%;background:var(--line2)}
.tmpl-body{height:90px;padding:6px}
.tmpl-hero-blk{border-radius:4px;height:40%;margin-bottom:5px}
.tmpl-items-row{display:grid;grid-template-columns:1fr 1fr;gap:3px;height:40%}
.tmpl-item-blk{border-radius:3px;background:var(--surface3)}
.tmpl-label{font-family:'Bricolage Grotesque',sans-serif;font-size:10.5px;font-weight:700;color:var(--ink);text-align:center;padding:5px 0}
.tmpl-customize{background:var(--o-glow);border:1px solid var(--o-mid);border-radius:var(--r);padding:11px 14px;display:flex;align-items:center;justify-content:space-between}
.tmpl-cust-text{font-size:12px;color:var(--o);font-weight:600}
.tmpl-cust-dots{display:flex;gap:5px}
.tmpl-cust-dot{width:14px;height:14px;border-radius:50%;border:2px solid #fff;box-shadow:var(--sh)} */

/* ══════════════════════════════════════
   DASHBOARD SHOWCASE
══════════════════════════════════════ */
#showcase{padding:120px 0}
.showcase-grid{display:grid;grid-template-columns:1fr 1fr;gap:72px;align-items:center;margin-top:72px}
.showcase-points{display:flex;flex-direction:column;gap:22px}
.showcase-point{display:flex;gap:14px;align-items:flex-start}
.sp-num{width:28px;height:28px;border-radius:50%;background:var(--o-glow);border:1.5px solid var(--o-mid);color:var(--o);font-family:'Bricolage Grotesque',sans-serif;font-size:12px;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.sp-title{font-family:'Bricolage Grotesque',sans-serif;font-size:15px;font-weight:700;color:var(--ink);margin-bottom:4px}
.sp-desc{font-size:13px;color:var(--muted);line-height:1.6}
.showcase-visual{background:var(--surface);border:1px solid var(--line2);border-radius:var(--rxl);box-shadow:var(--sh4);overflow:hidden;animation:float1 7s ease-in-out infinite}
.sv-header{background:var(--o);padding:16px 20px;display:flex;align-items:center;justify-content:space-between}
.sv-title{font-family:'Bricolage Grotesque',sans-serif;font-size:14px;font-weight:800;color:#fff}
.sv-live-tag{display:flex;align-items:center;gap:5px;font-size:10.5px;font-weight:600;color:rgba(255,255,255,.8)}
.sv-body{padding:18px}
.sv-stats-row{display:grid;grid-template-columns:repeat(3,1fr);gap:9px;margin-bottom:13px}
.sv-stat{background:var(--surface2);border-radius:var(--r);padding:11px;text-align:center}
.sv-stat-val{font-family:'Bricolage Grotesque',sans-serif;font-size:19px;font-weight:700;color:var(--ink)}
.sv-stat-lbl{font-size:10px;color:var(--muted)}
.sv-big-chart{background:var(--surface2);border-radius:var(--r);padding:13px;margin-bottom:11px}
.sv-chart-row-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:9px}
.sv-chart-title{font-size:11px;font-weight:600;color:var(--ink3)}
.sv-chart-delta{font-size:10.5px;font-weight:700;color:#16a34a;background:rgba(22,163,74,.1);padding:2px 8px;border-radius:99px}
.sv-line-chart{height:60px;position:relative}
.sv-line-chart svg{width:100%;height:100%}
.sv-orders-list{display:flex;flex-direction:column;gap:6px}
.sv-order{display:flex;align-items:center;gap:9px;padding:9px 11px;background:var(--surface2);border-radius:var(--r);border:1px solid var(--line)}

/* ══════════════════════════════════════
   WHY SECTION
══════════════════════════════════════ */


/* ══════════════════════════════════════
   WHY SECTION — LIGHT MODE
══════════════════════════════════════ */

#why {
  padding: 120px 0;
  background: #ffffff;
  position: relative;
  overflow: hidden;
}

/* subtle soft glows instead of dark neon */
.why-glow1 {
  position: absolute;
  right: -100px;
  top: -100px;
  width: 500px;
  height: 500px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(255,122,0,.08) 0%, transparent 65%);
  pointer-events: none;
}

.why-glow2 {
  position: absolute;
  left: -100px;
  bottom: -100px;
  width: 400px;
  height: 400px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(255,122,0,.05) 0%, transparent 65%);
  pointer-events: none;
}

.why-inner {
  position: relative;
  z-index: 1;
}

.why-header {
  text-align: center;
  margin-bottom: 64px;
}

/* switch to dark text */
.why-header .heading-lg {
  color: var(--ink);
}

.why-header .subtext {
  color: var(--muted);
  margin: 14px auto 0;
}

.why-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
}

.positions-grid {
  display: grid;
 grid-template-columns: repeat(4, 1fr);
 gap: 10px;

}

@media (max-width: 768px) {
  .positions-grid {
    grid-template-columns: 1fr;
  }
}

/* card becomes clean + elevated */
.why-card {
  background: #ffffff;
  border: 1px solid var(--line);
  border-radius: var(--rxl);
  padding: 28px;
  transition: all .25s;
  cursor: default;
}

/* hover = subtle lift, not glow */
.why-card:hover {
  border-color: rgba(255,122,0,.35);
  transform: translateY(-3px);
  box-shadow: var(--sho);
}

/* keep number but tone it properly */
.why-card-num {
  font-family: 'Bricolage Grotesque', sans-serif;
  font-size: 40px;
  font-weight: 800;
  color: var(--o) !important;
  opacity: .08;
  line-height: 1;
  margin-bottom: 10px;
}

/* icon = soft badge */
.why-card-icon {
  width: 40px;
  height: 40px;
  border-radius: 11px;
  background: rgba(255,122,0,.12);
  color: var(--o);
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 16px;
}

.why-card-icon svg {
  width: 20px;
  height: 20px;
}

.why-card-title {
  font-family: 'Bricolage Grotesque', sans-serif;
  font-size: 15.5px;
  font-weight: 700;
  color: var(--ink);
  margin-bottom: 9px;
}

.why-card-desc {
  font-size: 13.5px;
  color: var(--muted);
  line-height: 1.65;
}

/* #why{padding:120px 0;background:var(--ink);position:relative;overflow:hidden}
.why-glow1{position:absolute;right:-100px;top:-100px;width:500px;height:500px;border-radius:50%;background:radial-gradient(circle,rgba(255,122,0,.12) 0%,transparent 65%);pointer-events:none}
.why-glow2{position:absolute;left:-100px;bottom:-100px;width:400px;height:400px;border-radius:50%;background:radial-gradient(circle,rgba(255,122,0,.07) 0%,transparent 65%);pointer-events:none}
.why-inner{position:relative;z-index:1}
.why-header{text-align:center;margin-bottom:64px}
.why-header .heading-lg{color:#fff}
.why-header .subtext{color:rgba(255,255,255,.45);margin:14px auto 0}
.why-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
.why-card{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:var(--rxl);padding:28px;transition:all .25s;cursor:default}
.why-card:hover{background:rgba(255,122,0,.08);border-color:rgba(255,122,0,.25);transform:translateY(-3px)}
.why-card-num{font-family:'Bricolage Grotesque',sans-serif;font-size:40px;font-weight:800;color:var(--o);opacity:.15;line-height:1;margin-bottom:10px}
.why-card-icon{width:40px;height:40px;border-radius:11px;background:rgba(255,122,0,.14);color:var(--o);display:flex;align-items:center;justify-content:center;margin-bottom:16px}
.why-card-icon svg{width:20px;height:20px}
.why-card-title{font-family:'Bricolage Grotesque',sans-serif;font-size:15.5px;font-weight:700;color:#fff;margin-bottom:9px}
.why-card-desc{font-size:13.5px;color:rgba(255,255,255,.42);line-height:1.65} */

/* ══════════════════════════════════════
   TEMPLATES SECTION
══════════════════════════════════════ */
#templates{padding:120px 0}
.templates-scroll{display:grid;grid-template-columns:repeat(5,1fr);gap:14px;margin-top:56px}
.t-card{border:2px solid var(--line);border-radius:var(--rl);overflow:hidden;cursor:pointer;transition:all .25s}
.t-card:hover{border-color:var(--o);transform:translateY(-4px);box-shadow:var(--sho)}
.t-card-preview{height:150px;display:flex;flex-direction:column;overflow:hidden}
.t-card-topbar{height:22px;display:flex;align-items:center;gap:3px;padding:0 8px;border-bottom:1px solid var(--line)}
.t-card-topbar-dot{width:15px;height:5px;border-radius:50%}
.t-card-body{flex:1;padding:7px;display:flex;flex-direction:column;gap:4px}
.t-hero-block{border-radius:4px;height:42%}
.t-items{display:grid;grid-template-columns:1fr 1fr;gap:3px;flex:1}
.t-item-blk{border-radius:3px;background:var(--surface3)}
.t-card-footer{padding:10px;border-top:1px solid var(--line);text-align:center}
.t-card-name{font-family:'Bricolage Grotesque',sans-serif;font-size:12px;font-weight:700;color:var(--ink)}
.t-card-note{font-size:10.5px;color:var(--muted)}
.templates-customize-note{text-align:center;margin-top:28px;font-size:14px;color:var(--muted)}

/* ══════════════════════════════════════
   INTEGRATIONS
══════════════════════════════════════ */
#integrations{padding:120px 0;background:var(--surface2);border-top:1px solid var(--line);border-bottom:1px solid var(--line)}
.int-categories{display:flex;flex-direction:column;gap:28px;margin-top:56px}
.int-category-label{font-size:11.5px;font-weight:700;color:var(--o);text-transform:uppercase;letter-spacing:.07em;margin-bottom:11px;display:flex;align-items:center;gap:6px}
.int-cards-row{display:flex;flex-wrap:wrap;gap:9px}
.int-card{display:flex;align-items:center;gap:9px;background:var(--surface);border:1px solid var(--line);border-radius:var(--r);padding:9px 14px;cursor:default;transition:all .18s}
.int-card:hover{border-color:var(--o-mid);box-shadow:var(--sh2);transform:translateY(-1px)}
.int-card-ico{font-size:16px;flex-shrink:0}
.int-card-name{font-size:13px;font-weight:600;color:var(--ink)}
.int-card-type{font-size:10.5px;color:var(--muted)}

/* ══════════════════════════════════════
   HOW IT WORKS
══════════════════════════════════════ */
#how{padding:120px 0}
.how-steps{display:grid;grid-template-columns:repeat(3,1fr);gap:40px;margin-top:64px;position:relative}
.how-steps::after{content:'';position:absolute;top:54px;left:calc(100%/6 + 10px);right:calc(100%/6 + 10px);height:1.5px;background:linear-gradient(90deg,var(--o),var(--o-mid));border-radius:99px}
.how-step{text-align:center;position:relative}
.how-step-ball{width:108px;height:108px;border-radius:50%;background:linear-gradient(135deg,var(--o) 0%,var(--o-mid) 100%);margin:0 auto 28px;display:flex;align-items:center;justify-content:center;box-shadow:var(--sho);position:relative;z-index:1}
.how-step-ball svg{width:40px;height:40px;color:#fff}
.how-step-num{font-size:11px;font-weight:700;color:var(--o);letter-spacing:.07em;text-transform:uppercase;margin-bottom:8px}
.how-step-title{font-family:'Bricolage Grotesque',sans-serif;font-size:22px;font-weight:700;color:var(--ink);margin-bottom:10px}
.how-step-desc{font-size:14px;color:var(--muted);line-height:1.7;max-width:260px;margin:0 auto}

/* ══════════════════════════════════════
   PRICING
══════════════════════════════════════ */
#pricing{padding:120px 0;background:var(--surface2);border-top:1px solid var(--line)}
.pricing-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin-top:64px;align-items:start}
.price-card{background:var(--surface);border:1px solid var(--line);border-radius:var(--rxl);padding:34px 28px;position:relative;transition:all .25s}
.price-card:hover{box-shadow:var(--sh3);transform:translateY(-2px)}
.price-card.featured{background:var(--ink);border-color:var(--ink);transform:scale(1.03)}
.price-card.featured:hover{transform:scale(1.03) translateY(-2px)}
.price-rec-badge{position:absolute;top:-12px;left:50%;transform:translateX(-50%);background:var(--o);color:#fff;font-family:'Bricolage Grotesque',sans-serif;font-size:10.5px;font-weight:800;letter-spacing:.05em;text-transform:uppercase;padding:4px 14px;border-radius:99px;white-space:nowrap;box-shadow:var(--sho)}
.price-tier-name{font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--muted);margin-bottom:6px}
.price-card.featured .price-tier-name{color:rgba(255,255,255,.45)}
.price-tagline{font-size:13px;color:var(--muted);margin-bottom:22px;line-height:1.5}
.price-card.featured .price-tagline{color:rgba(255,255,255,.4)}
.price-amount{font-family:'Bricolage Grotesque',sans-serif;font-size:52px;font-weight:800;color:var(--ink);display:flex;align-items:flex-start;gap:2px;margin-bottom:4px;line-height:1}
.price-card.featured .price-amount{color:#fff}
.price-dollar{font-size:26px;margin-top:10px}
.price-period{font-family:'DM Sans',sans-serif;font-size:14px;font-weight:400;color:var(--muted);align-self:flex-end;margin-bottom:6px}
.price-card.featured .price-period{color:rgba(255,255,255,.35)}
.price-divider{height:1px;background:var(--line);margin:22px 0}
.price-card.featured .price-divider{background:rgba(255,255,255,.1)}
.price-features-list{list-style:none;display:flex;flex-direction:column;gap:9px;margin-bottom:28px}
.price-features-list li{display:flex;align-items:flex-start;gap:9px;font-size:13.5px;color:var(--body)}
.price-card.featured .price-features-list li{color:rgba(255,255,255,.7)}
.price-features-list li svg{width:15px;height:15px;color:#16a34a;flex-shrink:0;margin-top:2px}
.price-card.featured .price-features-list li svg{color:var(--o-mid)}
.btn-full{width:100%;justify-content:center}
.btn-white{background:#fff;color:var(--o);}
.btn-white:hover{background:var(--o-glow)}
.btn-outline-full{border:1.5px solid var(--line2);background:transparent;color:var(--ink2)}
.btn-outline-full:hover{background:var(--surface2)}

/* ══════════════════════════════════════
   TESTIMONIALS
══════════════════════════════════════ */
#testimonials{padding:120px 0}
.testi-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:22px;margin-top:64px}
.testi-card{background:var(--surface);border:1px solid var(--line);border-radius:var(--rxl);padding:30px;transition:all .22s}
.testi-card:hover{box-shadow:var(--sh3);transform:translateY(-3px)}
.testi-stars{color:var(--o);font-size:14px;letter-spacing:2px;margin-bottom:14px}
.testi-quote{font-family:'Bricolage Grotesque',sans-serif;font-size:16.5px;font-weight:400;color:var(--ink3);line-height:1.65;margin-bottom:22px;font-style:italic}
.testi-divider{height:1px;background:var(--line);margin-bottom:18px}
.testi-author{display:flex;align-items:center;gap:11px}
.testi-avatar{width:42px;height:42px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:'Bricolage Grotesque',sans-serif;font-size:14px;font-weight:800;color:#fff;flex-shrink:0}
.testi-name{font-family:'Bricolage Grotesque',sans-serif;font-size:14px;font-weight:700;color:var(--ink)}
.testi-role{font-size:12px;color:var(--muted)}

/* ══════════════════════════════════════
   FINAL CTA
══════════════════════════════════════ */
#cta{padding:120px 0;position:relative;overflow:hidden}
.cta-glow-bg{position:absolute;inset:0;background:linear-gradient(135deg,var(--o) 0%,#CC4400 100%)}
.cta-noise{position:absolute;inset:0;background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.04'/%3E%3C/svg%3E");opacity:.5}
.cta-orb1{position:absolute;right:-80px;top:-80px;width:400px;height:400px;border-radius:50%;background:rgba(255,255,255,.08);pointer-events:none}
.cta-orb2{position:absolute;left:-60px;bottom:-60px;width:300px;height:300px;border-radius:50%;background:rgba(255,255,255,.05);pointer-events:none}
.cta-inner{position:relative;z-index:1;text-align:center}
.cta-h2{color:#fff;font-size:clamp(38px,5.5vw,68px);margin-bottom:18px}
.cta-sub{color:rgba(255,255,255,.65);font-size:18px;max-width:460px;margin:0 auto 44px;text-align:center;line-height:1.7}
.cta-actions{display:flex;align-items:center;justify-content:center;gap:14px;flex-wrap:wrap}
.cta-note{margin-top:20px;font-size:13px;color:rgba(255,255,255,.38)}

/* ══════════════════════════════════════
   CONTACT
══════════════════════════════════════ */
#contact{padding:120px 0}
.contact-grid{display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:start}
.contact-info{display:flex;flex-direction:column;gap:22px;margin-top:32px}
.contact-info-item{display:flex;align-items:flex-start;gap:13px}
.ci-icon{width:38px;height:38px;border-radius:10px;background:var(--o-glow);color:var(--o);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.ci-icon svg{width:17px;height:17px}
.ci-label{font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.07em;margin-bottom:2px}
.ci-value{font-family:'Bricolage Grotesque',sans-serif;font-size:14.5px;font-weight:600;color:var(--ink)}
.contact-form-card{background:var(--surface);border:1px solid var(--line2);border-radius:var(--rxl);padding:36px;box-shadow:var(--sh2)}
.form-title{font-family:'Bricolage Grotesque',sans-serif;font-size:20px;font-weight:700;color:var(--ink);margin-bottom:24px}
.form-body{display:flex;flex-direction:column;gap:14px}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.form-group{display:flex;flex-direction:column;gap:5px}
.form-label{font-size:12.5px;font-weight:600;color:var(--ink3)}
.form-input,.form-textarea,.form-select{padding:11px 13px;border:1.5px solid var(--line2);border-radius:var(--r);font-family:'DM Sans',sans-serif;font-size:14px;color:var(--ink);background:var(--surface);outline:none;transition:border-color .15s,box-shadow .15s;resize:vertical}
.form-input:focus,.form-textarea:focus,.form-select:focus{border-color:var(--o);box-shadow:0 0 0 3px rgba(255,122,0,.12)}
.form-textarea{min-height:90px}

/* ══════════════════════════════════════
   FOOTER
══════════════════════════════════════ */
footer{background:var(--ink2);padding:72px 0 36px}
.footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr 1fr 1fr;gap:40px;margin-bottom:56px}
.footer-brand{}
.footer-logo{font-family:'Bricolage Grotesque',sans-serif;font-size:20px;font-weight:800;letter-spacing:-.04em;color:#fff;display:flex;align-items:center;gap:8px;margin-bottom:12px}
.footer-logo-mark{width:28px;height:28px;border-radius:7px;background:var(--o);display:flex;align-items:center;justify-content:center}
.footer-logo-mark svg{width:14px;height:14px;color:#fff}
.footer-tagline{font-size:13.5px;color:rgba(255,255,255,.32);line-height:1.65;max-width:260px;margin-bottom:22px}
.footer-newsletter{display:flex;gap:8px}
.footer-email-input{flex:1;padding:10px 13px;border-radius:9px;border:1px solid rgba(255,255,255,.1);background:rgba(255,255,255,.06);color:#fff;font-family:'DM Sans',sans-serif;font-size:13px;outline:none;transition:border-color .15s}
.footer-email-input::placeholder{color:rgba(255,255,255,.22)}
.footer-email-input:focus{border-color:var(--o)}
.footer-subscribe-btn{padding:10px 16px;border-radius:9px;background:var(--o);color:#fff;font-family:'DM Sans',sans-serif;font-size:13px;font-weight:600;border:none;cursor:pointer;white-space:nowrap;transition:background .18s}
.footer-subscribe-btn:hover{background:var(--o-h)}
.footer-col-title{font-family:'Bricolage Grotesque',sans-serif;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#fff;margin-bottom:15px}
.footer-links{list-style:none;display:flex;flex-direction:column;gap:9px}
.footer-links a{font-size:13.5px;color:rgba(255,255,255,.38);transition:color .15s}
.footer-links a:hover{color:rgba(255,255,255,.8)}
.footer-bottom{border-top:1px solid rgba(255,255,255,.07);padding-top:26px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px}
.footer-copy{font-size:13px;color:rgba(255,255,255,.22)}
.footer-socials{display:flex;gap:9px}
.social-icon{width:34px;height:34px;border-radius:8px;border:1px solid rgba(255,255,255,.1);background:rgba(255,255,255,.04);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,.38);transition:all .18s}
.social-icon:hover{background:var(--o);color:#fff;border-color:var(--o)}
.social-icon svg{width:15px;height:15px}

/* ══════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════ */
@media(max-width:900px){
  .hdf-body{grid-template-columns:1fr}
  .hdf-sidebar{display:none}
  .hdf-kpi-row{grid-template-columns:1fr 1fr}
  .features-layout { grid-template-columns: 1fr; }
  .features-nav { position: static; flex-direction: row; overflow-x: auto; flex-wrap: nowrap; gap: 8px; }
  .feature-nav-item { min-width: 180px; flex-direction: column; align-items: flex-start; }
  /* .features-sticky-wrap{grid-template-columns:1fr} */
  .feat-preview-panel{display:none}
  .sol-grid,.showcase-grid,.contact-grid,.pricing-grid,.testi-grid,.why-grid{grid-template-columns:1fr}
  .price-card.featured{transform:none}
  .how-steps::after{display:none}
  .how-steps{grid-template-columns:1fr}
  .footer-grid{grid-template-columns:1fr 1fr}
  .templates-scroll{grid-template-columns:repeat(3,1fr)}
  .hero-float{display:none}
}
@media(max-width:600px){
  .nav-links{display:none}
  .hero-dash-wrap{display:none}
  .footer-grid{grid-template-columns:1fr}
  .templates-scroll{grid-template-columns:1fr 1fr}
}
</style>
</head>
<body>

<!-- ══════════ NAV ══════════ -->
<nav id="nav">
  <div class="container">
    <div class="nav-inner">
      <!-- <a href="#" class="nav-logo">
        <div class="nav-logo-mark">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
            <circle cx="12" cy="12" r="9"/>
            <path d="M12 8v4l2.5 2.5"/>
          </svg>
        </div>
        OrderFlow
      </a> -->
      <a href="/">
            <img src="{{ asset('/themes/default/Platepilot_Logo.png') }}" width="170">
      </a>
      <ul class="nav-links">
        <li><a href="#solutions">Solutions</a></li>
        <li><a href="#features">Features</a></li>
        <!-- <li><a href="#templates">Templates</a></li> -->
        <li><a href="#integrations">Integrations</a></li>
        <li><a href="#pricing">Pricing</a></li>
      </ul>
      <div class="nav-ctas">
        <!-- <a href="#" class="nav-signin">Sign in</a> -->
        <a href="admin/login" class="btn btn-primary btn-sm">Sign in</a>
      </div>
    </div>
  </div>
</nav>

<!-- ══════════ HERO ══════════ -->
<section id="hero">
  <div class="hero-glow"></div>
  <div class="hero-grid"></div>
  <div class="container">
    <div class="hero-inner">

      <div class="hero-badge fade-up">
        <span class="eyebrow"><span class="eyebrow-dot"></span>Now in Public Beta · 5+ Businesses Onboarded</span>
      </div>

      <h1 class="heading-xl hero-h1 fade-up d1">
        Launch Your Own<br/>
        Online Ordering Platform<br/>
        <span class="italic">Built for Growth.</span>
      </h1>

      <p class="subtext hero-sub fade-up d2">
        Take back control from third-party marketplaces. Accept direct orders, run your brand, and own every customer relationship — from one powerful platform.
      </p>

      <div class="hero-actions fade-up d3">
        <a href="#contact" class="btn btn-primary btn-lg">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
          Contact Sales
        </a>
        <!-- <a href="#contact" class="btn btn-ghost btn-lg">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          Book Demo
        </a> -->
      </div>
      <!-- <p class="hero-note fade-up d4">No credit card · 14-day free trial · Cancel anytime</p> -->

      <!-- HERO DASHBOARD MOCKUP -->
      <div class="hero-dash-wrap fade-up d5" style="position:relative">
        <!-- Floating widgets -->
        <div class="hero-float hf1" style="z-index: 999 !important;">
          <div class="hf-label">Today's Revenue</div>
          <div class="hf-value">14,230KD</div>
          <div class="hf-sub">↑ 18.4% vs yesterday</div>
        </div>
        <div class="hero-float hf2" style="z-index: 999 !important;">>
          <div class="hf-label">Live Orders</div>
          <div class="hf-value">42</div>
          <div style="margin-top:5px"><span class="hf-pill">Streaming live</span></div>
        </div>
        <div class="hero-float hf3" style="z-index: 999 !important;">>
          <div class="hf-label">Avg. Delivery</div>
          <div class="hf-value">26 min</div>
          <div class="hf-sub">↓ 5 min faster</div>
        </div>

        <div class="hero-dash-frame">
          <div class="hdf-bar">
            <div class="hdf-dots"><div class="hdf-dot"></div><div class="hdf-dot"></div><div class="hdf-dot"></div></div>
            <div class="hdf-url">platepilots.com/admin/dashboard</div>
          </div>
          <div class="hdf-body">
            <div class="hdf-sidebar">
              <div class="hdf-brand">
                <div class="hdf-brand-ico">P</div>
                <span class="hdf-brand-name">Platepilot</span>
              </div>
              <div class="hdf-nav-label">Main</div>
              <div class="hdf-nav-item active">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
                Overview
              </div>
              <div class="hdf-nav-item">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                Live Orders
              </div>
              <div class="hdf-nav-item">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                Analytics
              </div>
              <div class="hdf-nav-item">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                Customers
              </div>
              <div class="hdf-nav-label">Settings</div>
              <div class="hdf-nav-item">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                Branches
              </div>
              <div class="hdf-nav-item">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                Payments
              </div>
            </div>
            <div class="hdf-main">
              <div class="hdf-top-row">
                <div class="hdf-page-title">Today's Overview</div>
                <div class="hdf-date">May 28, 2025 ▾</div>
              </div>
              <div class="hdf-kpi-row">
                <div class="hdf-kpi"><div class="hdf-kpi-label">Orders</div><div class="hdf-kpi-val">312</div><div class="hdf-kpi-delta">↑ 14%</div></div>
                <div class="hdf-kpi"><div class="hdf-kpi-label">Revenue</div><div class="hdf-kpi-val">$14.2K</div><div class="hdf-kpi-delta">↑ 22%</div></div>
                <div class="hdf-kpi"><div class="hdf-kpi-label">Avg. Value</div><div class="hdf-kpi-val">$45.6</div><div class="hdf-kpi-delta">↑ 6%</div></div>
                <div class="hdf-kpi"><div class="hdf-kpi-label">New Users</div><div class="hdf-kpi-val">41</div><div class="hdf-kpi-delta">↑ 28%</div></div>
              </div>
              <div class="hdf-charts-row">
                <!-- <div class="hdf-chart-card">
                  <div class="hdf-chart-title">Hourly Revenue</div>
                  <div class="mini-bars">
                    <div style="flex:1"><div class="mb" style="height:30%;animation-delay:.00s"></div><div class="mb-x">9a</div></div>
                    <div style="flex:1"><div class="mb" style="height:48%;animation-delay:.05s"></div><div class="mb-x">10</div></div>
                    <div style="flex:1"><div class="mb" style="height:65%;animation-delay:.10s"></div><div class="mb-x">11</div></div>
                    <div style="flex:1"><div class="mb hi" style="height:98%;animation-delay:.15s"></div><div class="mb-x">12</div></div>
                    <div style="flex:1"><div class="mb hi" style="height:100%;animation-delay:.20s"></div><div class="mb-x">1p</div></div>
                    <div style="flex:1"><div class="mb hi" style="height:82%;animation-delay:.25s"></div><div class="mb-x">2</div></div>
                    <div style="flex:1"><div class="mb" style="height:58%;animation-delay:.30s"></div><div class="mb-x">3</div></div>
                    <div style="flex:1"><div class="mb hi" style="height:73%;animation-delay:.35s"></div><div class="mb-x">4</div></div>
                    <div style="flex:1"><div class="mb hi" style="height:88%;animation-delay:.40s"></div><div class="mb-x">5</div></div>
                  </div>
                </div> -->
                <div class="hdf-chart-card">
                  <div class="hdf-chart-title">Hourly Revenue</div>
                  <div style="position:relative;width:100%;height:100px">
                    <canvas id="revenueChart"></canvas>
                  </div>
                </div>
                <div class="hdf-chart-card">
                  <div class="hdf-chart-title">Live Orders</div>
                  <div class="orders-feed">
                    <div class="order-item">
                      <div class="order-avatar" style="background:var(--o)">JD</div>
                      <div class="order-info"><div class="order-name">James D.</div><div class="order-detail">Pad Thai × 2</div></div>
                      <span class="order-badge ob-green">Delivered</span>
                    </div>
                    <div class="order-item">
                      <div class="order-avatar" style="background:#8b5cf6">SK</div>
                      <div class="order-info"><div class="order-name">Sara K.</div><div class="order-detail">Ramen × 1</div></div>
                      <span class="order-badge ob-orange">En Route</span>
                    </div>
                    <div class="order-item">
                      <div class="order-avatar" style="background:#3b82f6">MR</div>
                      <div class="order-info"><div class="order-name">Mike R.</div><div class="order-detail">Combo × 3</div></div>
                      <span class="order-badge ob-blue">Preparing</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div><!-- /hero-dash-wrap -->

    </div>
  </div>
</section>

<!-- ══════════ TRUST ══════════ -->
<section id="trust">
  <div class="container">
    <div class="trust-inner">
      <p class="trust-text">Powering restaurants, cloud kitchens, cafés, and retail food brands across Kuwait</p>
      <div class="trust-logos">
        <span class="trust-logo">TryGuardi</span>
        <span class="trust-logo">Raneem</span>
        <span class="trust-logo">Whapex</span>
        <span class="trust-logo">Marx</span>
        <!-- <span class="trust-logo">FreshBowl</span>
        <span class="trust-logo">TableOne</span>
        <span class="trust-logo">NoodleHouse</span> -->
      </div>
    </div>
  </div>
</section>

<!-- ══════════ SOLUTIONS ══════════ -->

<section id="solutions">
  <div class="why-glow1"></div>
  <div class="why-glow2"></div>
  <div class="container">
    <div class="why-inner">
      <div class="why-header fade-up">
        <span class="eyebrow" style="background:rgba(255,122,0,.14);border-color:rgba(255,122,0,.3);color:var(--o)"><span class="eyebrow-dot"></span>Core Solutions</span>
        <h2 class="heading-lg" style="color: #000 !important;margin-top:16px;margin-bottom:14px">Everything you need to <br/>run your business</h2>
        <p class="subtext" style="margin:0 auto;text-align:center">Beyond just ordering a complete operations platform<br/>built for serious restaurants and retail businesses.</p>
      </div>
      <div class="positions-grid">
        <div class="why-card fade-up">
          <!-- <div class="why-card-num">01</div> -->
          <div class="why-card-icon">
            <x-lucide-layout-dashboard class="input-icon" />
            <!-- <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg> -->
          </div>
          <div class="why-card-title">Robust Admin Dashboard</div>
          <div class="why-card-desc">A centralized command center for your entire operation live orders, branch performance, team activity, and revenue trends in one view.</div>
        </div>
        <div class="why-card fade-up d1">
          <!-- <div class="why-card-num">02</div> -->
          <div class="why-card-icon">
            <x-lucide-chart-no-axes-column class="input-icon" />
            <!-- <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg> -->
          </div>
          <div class="why-card-title">Reports & Business Insights</div>
          <div class="why-card-desc">Export daily, weekly, and monthly reports on sales, top dishes, delivery performance, and customer behavior to make smarter decisions faster.</div>
        </div>
        <div class="why-card fade-up d2">
          <!-- <div class="why-card-num">03</div> -->
          <div class="why-card-icon">
            <x-lucide-megaphone class="input-icon" />
            <!-- <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg> -->
          </div>
          <div class="why-card-title">Promotions & Marketing Tools</div>
          <div class="why-card-desc">Create discount codes, flash sales, loyalty programs, and push notifications to re-engage customers and increase repeat order rates.</div>
        </div>
        <div class="why-card fade-up d3">
          <!-- <div class="why-card-num">04</div> -->
          <div class="why-card-icon">
            <x-lucide-credit-card class="input-icon" />
            <!-- <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg> -->
          </div>
          <div class="why-card-title">Multiple Payment Integrations</div>
          <div class="why-card-desc">Connect Payzah, Upayments, MyFatoorah and more. Give customers their preferred way to pay locally.</div>
        </div>
        <div class="why-card fade-up d4">
          <!-- <div class="why-card-num">05</div> -->
          <div class="why-card-icon">
             <x-lucide-package class="input-icon" />
            <!-- <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg> -->
          </div>
          <div class="why-card-title">Logistics & Delivery Integrations</div>
          <div class="why-card-desc">Connect with Armada and more for automated driver dispatch, live tracking, and delivery zone management at scale.</div>
        </div>
        <div class="why-card fade-up d5">
          <!-- <div class="why-card-num">06</div> -->
          <div class="why-card-icon">
             <x-lucide-users class="input-icon" />
            <!-- <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg> -->
          </div>
          <div class="why-card-title">Operations & CRM Integrations</div>
          <div class="why-card-desc">Sync with your existing CRM, POS, and ERP tools to keep your customer data and operations fully connected across every touchpoint.</div>
        </div>
        <div class="why-card fade-up d6">
          <!-- <div class="why-card-num">07</div> -->
          <div class="why-card-icon">
             <x-lucide-lock class="input-icon" />
            <!-- <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg> -->
          </div>
          <div class="why-card-title">Role-Based Access Control</div>
          <div class="why-card-desc">Fine-grained permissions for every team member. Admins, managers, vendors, and finance teams each see exactly what they need nothing more, nothing less.</div>
        </div>
        <div class="why-card fade-up d7">
          <!-- <div class="why-card-num">08</div> -->
          <div class="why-card-icon">
             <x-lucide-globe class="input-icon" />
            <!-- <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg> -->
          </div>
          <div class="why-card-title">Cloud-Based, Anywhere Access</div>
          <div class="why-card-desc">Run your business from any device, anywhere in the world. Platepilot is fully cloud-native with 99.9% uptime SLA, automatic backups, and enterprise-grade security.</div>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- <section id="solutions">
  <div class="container">
    <div class="content-gap fade-up" style="text-align:center">
      <span class="eyebrow"><span class="eyebrow-dot"></span>Core Solution</span>
      <h2 class="heading-lg" style="margin-top:16px;margin-bottom:16px">Your complete online ordering<br/>system, ready in days</h2>
      <p class="subtext" style="margin:0 auto">Everything your customers need to order directly from you — seamlessly built into your own branded experience.</p>
    </div>
    <div class="sol-grid">
      <div class="sol-items">
        <div class="sol-item fade-up">
          <div class="sol-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
          <div><div class="sol-title">Delivery & Pickup Ordering</div><div class="sol-desc">Let customers choose how they receive their order — with live tracking, estimated times, and automated driver dispatch built in.</div></div>
        </div>
        <div class="sol-item fade-up d1">
          <div class="sol-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg></div>
          <div><div class="sol-title">Secure Payment Gateway</div><div class="sol-desc">Accept cards, digital wallets, and cash-on-delivery. PCI-compliant with fraud protection and 15+ payment providers globally.</div></div>
        </div>
        <div class="sol-item fade-up d2">
          <div class="sol-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg></div>
          <div><div class="sol-title">Mobile-Optimized Checkout</div><div class="sol-desc">70%+ of food orders happen on mobile. Our checkout flow is designed mobile-first — fast, smooth, and conversion-optimized.</div></div>
        </div>
        <div class="sol-item fade-up d3">
          <div class="sol-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg></div>
          <div><div class="sol-title">Real-Time Order Management</div><div class="sol-desc">Your kitchen receives instant order notifications. Accept, prepare, assign, and track every order from any device — live.</div></div>
        </div>
      </div>
      <div class="fade-up d2" style="display:flex;align-items:center;justify-content:center">
        <div class="phone-outer">
          <div class="phone-float-badge">
            <div class="hf-label">Payment</div>
            <div class="hf-value" style="font-size:14px;font-family:'Bricolage Grotesque',sans-serif;font-weight:700;color:var(--ink)">✓ Confirmed</div>
            <div class="hf-sub">$38.50 via Apple Pay</div>
          </div>
          <div class="phone-body">
            <div class="phone-screen">
              <div class="phone-header">
                <div class="phone-restaurant">NoodleHouse</div>
                <div class="phone-delivery-tag">Delivery · Est. 26 mins</div>
              </div>
              <div class="phone-body-content">
                <div class="phone-section-label">🔥 Popular</div>
                <div class="phone-menu-items">
                  <div class="phone-menu-item"><div class="pmi-emoji">🍜</div><div><div class="pmi-name">Signature Ramen</div><div class="pmi-price">$14.50</div></div><div class="pmi-add">+</div></div>
                  <div class="phone-menu-item"><div class="pmi-emoji">🥢</div><div><div class="pmi-name">Pad Thai</div><div class="pmi-price">$12.00</div></div><div class="pmi-add">+</div></div>
                  <div class="phone-menu-item"><div class="pmi-emoji">🥟</div><div><div class="pmi-name">Gyoza × 6</div><div class="pmi-price">$9.00</div></div><div class="pmi-add">+</div></div>
                </div>
                <div class="phone-section-label">🌶️ Specials</div>
                <div class="phone-menu-items">
                  <div class="phone-menu-item"><div class="pmi-emoji">🍲</div><div><div class="pmi-name">Spicy Hot Pot</div><div class="pmi-price">$16.00</div></div><div class="pmi-add">+</div></div>
                </div>
              </div>
              <div class="phone-cta">View Cart · 3 items · $38.50 →</div>
            </div>
          </div>
          <div class="phone-float-badge2">
            <div class="hf-label">Delivery Status</div>
            <div style="font-family:'Bricolage Grotesque',sans-serif;font-size:13px;font-weight:700;color:var(--ink)">🛵 En Route</div>
            <div class="hf-sub">Arrives in ~9 min</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section> -->

<!-- ══════════ STICKY FEATURES ══════════ -->
<!-- ══════════════════════════════════════
     INTERACTIVE FEATURES SECTION
══════════════════════════════════════ -->
<section id="features" class="section-gap">
  <div class="container">
    <div class="features-header fade-up">
      <div class="eyebrow"><div class="eyebrow-dot"></div>Features</div>
      <h2 class="heading-lg" style="color: #fff; margin-bottom: 16px;">Every tool to grow your restaurant business</h2>
      <p class="subtext" style="color: rgba(255,255,255,0.4); margin: 0 auto 40px; text-align: center;">From delivery to analytics to multi-branch control — it's all here.</p>
    </div>

    <div class="features-layout">
      <!-- Sidebar Navigation -->
      <div class="features-nav" id="featuresNav">
        <div class="feature-nav-item active" data-panel="delivery">
          <div class="fnav-indicator"></div>
          <!-- <div class="fnav-icon">🚗</div> -->
           <x-lucide-package class="fnav-icon" />
          <div class="fnav-text">
            <div class="fnav-title">Delivery & Pickup</div>
            <div class="fnav-desc">Real-time tracking and smart dispatch</div>
          </div>
        </div>
        <div class="feature-nav-item" data-panel="payments">
          <div class="fnav-indicator"></div>
          <!-- <div class="fnav-icon">💳</div> -->
            <x-lucide-credit-card class="fnav-icon" />
          <div class="fnav-text">
            <div class="fnav-title">Payment Gateway</div>
            <div class="fnav-desc">Cards, wallets, and local methods</div>
          </div>
        </div>
        <div class="feature-nav-item" data-panel="analytics">
          <div class="fnav-indicator"></div>
          <!-- <div class="fnav-icon">📊</div> -->
           <x-lucide-chart-no-axes-column class="fnav-icon" />
          <div class="fnav-text">
            <div class="fnav-title">Reports & Insights</div>
            <div class="fnav-desc">Revenue charts and customer trends</div>
          </div>
        </div>
        <div class="feature-nav-item" data-panel="promos">
          <div class="fnav-indicator"></div>
          <!-- <div class="fnav-icon">🎯</div> -->
           <x-lucide-megaphone class="fnav-icon" />
          <div class="fnav-text">
            <div class="fnav-title">Promotions & Marketing</div>
            <div class="fnav-desc">Discounts, loyalty, and campaigns</div>
          </div>
        </div>
        <!-- <div class="feature-nav-item" data-panel="branches">
          <div class="fnav-indicator"></div>
          <div class="fnav-icon">🏢</div>
          <div class="fnav-text">
            <div class="fnav-title">Multi-branch Control</div>
            <div class="fnav-desc">Manage all locations from one dashboard</div>
          </div>
        </div> -->
        <div class="feature-nav-item" data-panel="integrations">
          <div class="fnav-indicator"></div>
          <!-- <div class="fnav-icon">🔗</div> -->
           <x-lucide-cable class="fnav-icon" />
          <div class="fnav-text">
            <div class="fnav-title">CRM & Integrations</div>
            <div class="fnav-desc">Connect your entire tech stack</div>
          </div>
        </div>
        <!-- <div class="feature-nav-item" data-panel="templates">
          <div class="fnav-indicator"></div>
          <div class="fnav-icon">🎨</div>
          <div class="fnav-text">
            <div class="fnav-title">Website Templates</div>
            <div class="fnav-desc">Beautiful layouts for every brand</div>
          </div>
        </div> -->
      </div>

      <!-- Preview Panels -->
      <div class="features-preview">
        <!-- 1. Delivery -->
        <div class="feature-panel active" id="panel-delivery">
          <div class="panel-info">
            <div class="panel-title">Delivery & Pickup Management</div>
            <p class="panel-desc">Real-time order tracking, automated dispatch, and delivery zone setup for a seamless customer experience.</p>
          </div>
          <div class="panel-visual">
            <div class="delivery-stat-row">
              <div class="dv-card">
                <div class="dv-card-lbl">Active Deliveries</div>
                <div class="dv-card-val">12</div>
                <div class="dv-card-sub">Avg ETA: 22 min</div>
              </div>
              <div class="dv-card">
                <div class="dv-card-lbl">Pickups Queued</div>
                <div class="dv-card-val">7</div>
                <div class="dv-card-sub" style="color:#4ADE80">Next: 4 min</div>
              </div>
            </div>
            <div class="delivery-order-list">
              <div class="dv-order">
                <div class="dv-order-emoji">🛵</div>
                <div class="dv-order-info">
                  <div class="dv-order-name">Order #2845 — Ahmad R.</div>
                  <div class="dv-order-addr">Al Hamra District, Block 4</div>
                </div>
                <div class="dv-order-eta">8 min</div>
              </div>
              <div class="dv-order">
                <div class="dv-order-emoji">🏪</div>
                <div class="dv-order-info">
                  <div class="dv-order-name">Pickup #2840 — Noura M.</div>
                  <div class="dv-order-addr">Branch: Marina Mall</div>
                </div>
                <div class="dv-order-eta" style="color:#4ADE80">Ready</div>
              </div>
            </div>
          </div>
        </div>

        <!-- 2. Payments -->
        <div class="feature-panel" id="panel-payments">
          <div class="panel-info">
            <div class="panel-title">Secure Payment Gateway</div>
            <p class="panel-desc">Accept cards, KNET, Apple Pay, and local methods with PCI-DSS Level 1 security and instant settlements.</p>
          </div>
          <div class="panel-visual">
            <div class="pay-methods">
              <div class="pay-method">💳 Visa</div>
              <div class="pay-method">🌐 KNET</div>
              <div class="pay-method">🍎 Pay</div>
            </div>
            <div class="pay-recent">
              <div class="pay-tx"><span>Order #2847 · Visa</span><strong>+$28.50</strong></div>
              <div class="pay-tx"><span>Order #2846 · KNET</span><strong>+$19.80</strong></div>
            </div>
          </div>
        </div>

        <!-- 3. Analytics -->
        <div class="feature-panel" id="panel-analytics">
          <div class="panel-info">
            <div class="panel-title">Reports & Insights</div>
            <p class="panel-desc">Analyze revenue trends, menu performance, and customer repeat rates in real-time.</p>
          </div>
          <div class="panel-visual">
            <div class="analytics-kpis">
              <div class="akpi"><div class="akpi-val">$84.2K</div><div class="akpi-lbl">Revenue</div></div>
              <div class="akpi"><div class="akpi-val">3,240</div><div class="akpi-lbl">Orders</div></div>
              <div class="akpi"><div class="akpi-val">68%</div><div class="akpi-lbl">Repeat</div></div>
            </div>
            <div class="mini-bars" style="height: 100px; padding-top: 20px;">
              <div class="mb" style="height:40%"></div>
              <div class="mb" style="height:60%"></div>
              <div class="mb hi" style="height:90%"></div>
              <div class="mb" style="height:50%"></div>
              <div class="mb" style="height:75%"></div>
            </div>
          </div>
        </div>

        <!-- 4. Promos -->
        <div class="feature-panel" id="panel-promos">
          <div class="panel-info">
            <div class="panel-title">Marketing & Loyalty</div>
            <p class="panel-desc">Drive repeat orders with automated campaigns, flash sales, and targeted discount codes.</p>
          </div>
          <div class="panel-visual">
            <div class="promo-card-alt">
              <div class="p-tag">ACTIVE</div>
              <div class="p-name">Weekend Special</div>
              <div class="p-stat">20% Off · 142 redeemed</div>
            </div>
            <div class="promo-perf-row">
              <div class="p-bar"><div class="p-fill" style="width: 72%"></div></div>
              <span class="p-val">72% Open Rate</span>
            </div>
          </div>
        </div>

        <!-- 5. Branches -->
        <!-- <div class="feature-panel" id="panel-branches">
          <div class="panel-info">
            <div class="panel-title">Multi-branch Control</div>
            <p class="panel-desc">Keep central oversight while empowering branch managers to handle local operations.</p>
          </div>
          <div class="panel-visual">
            <div class="branch-item-mini">
              <div class="b-dot online"></div>
              <span>Marina Mall</span>
              <strong>$22,400</strong>
            </div>
            <div class="branch-item-mini">
              <div class="b-dot online"></div>
              <span>Salmiya</span>
              <strong>$18,900</strong>
            </div>
            <div class="branch-item-mini">
              <div class="b-dot offline"></div>
              <span>Jahra</span>
              <span class="muted">Offline</span>
            </div>
          </div>
        </div> -->

        <!-- 6. Integrations -->
        <div class="feature-panel" id="panel-integrations">
          <div class="panel-info">
            <div class="panel-title">Ecosystem & CRM</div>
            <p class="panel-desc">Connect with Marx, Whapex and other essential business tools.</p>
          </div>
           <div class="panel-visual">
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px">
              <div style="background:rgba(255,255,255,.06);border-radius:12px;padding:16px;text-align:center;border:1px solid rgba(255,255,255,.07)"><div style="font-size:24px;margin-bottom:6px">📦</div><div style="font-size:12px;color:rgba(255,255,255,.5);font-weight:600">Marx</div></div>
              <div style="background:rgba(255,255,255,.06);border-radius:12px;padding:16px;text-align:center;border:1px solid rgba(255,255,255,.07)"><div style="font-size:24px;margin-bottom:6px">📧</div><div style="font-size:12px;color:rgba(255,255,255,.5);font-weight:600">Whapex</div></div>
              <div style="background:rgba(255,122,0,.12);border-radius:12px;padding:16px;text-align:center;border:1px solid rgba(255,122,0,.2)"><div style="font-size:24px;margin-bottom:6px">🔗</div><div style="font-size:12px;color:var(--orange);font-weight:600">+ more</div></div>
            </div>
          </div>
          <!-- <div class="panel-visual">
            <div class="int-grid-mini">
              <div class="int-box">📦</div>
              <div class="int-box">📊</div>
              <div class="int-box">📧</div>
              <div class="int-box">🚚</div>
              <div class="int-box">💬</div>
              <div class="int-box" style="background:var(--o); color:#fff;">+40</div>
            </div>
          </div> -->
        </div>

        <!-- 7. Templates -->
        <!-- <div class="feature-panel" id="panel-templates">
          <div class="panel-info">
            <div class="panel-title">Beautiful Templates</div>
            <p class="panel-desc">Launch a conversion-optimized ordering site in minutes. No coding required.</p>
          </div>
          <div class="panel-visual">
            <div class="tmpl-row-mini">
              <div class="tmpl-box-mini">🍔</div>
              <div class="tmpl-box-mini active">☕</div>
              <div class="tmpl-box-mini">🏢</div>
            </div>
          </div>
        </div> -->

      </div>
    </div>
  </div>
</section>
<!-- <section id="features">
  <div class="container">
    <div class="features-header fade-up">
      <span class="eyebrow"><span class="eyebrow-dot"></span>Platform Features</span>
      <h2 class="heading-lg" style="margin-top:16px;margin-bottom:16px">One platform.<br/>Every capability you need.</h2>
      <p class="subtext" style="margin:0 auto">Click any feature to see it come to life. Built for restaurant operators, not developers.</p>
    </div>
    <div class="features-sticky-wrap">
      <div class="features-nav">
        <div class="feat-nav-items" id="featNavItems">
          <div class="feat-nav-item active" data-panel="delivery">
            <div class="feat-nav-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg></div>
            <div><div class="feat-nav-text-title">Delivery & Pickup</div><div class="feat-nav-text-desc">Live tracking, driver dispatch, zone management</div></div>
          </div>
          <div class="feat-nav-item" data-panel="payments">
            <div class="feat-nav-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg></div>
            <div><div class="feat-nav-text-title">Payment Gateway</div><div class="feat-nav-text-desc">15+ providers, wallets, fraud protection</div></div>
          </div>
          <div class="feat-nav-item" data-panel="reports">
            <div class="feat-nav-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></div>
            <div><div class="feat-nav-text-title">Reports & Insights</div><div class="feat-nav-text-desc">Revenue analytics, customer trends, exports</div></div>
          </div>
          <div class="feat-nav-item" data-panel="promotions">
            <div class="feat-nav-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg></div>
            <div><div class="feat-nav-text-title">Promotions & Marketing</div><div class="feat-nav-text-desc">Discount codes, loyalty, push campaigns</div></div>
          </div>
         
          <div class="feat-nav-item" data-panel="crm">
            <div class="feat-nav-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/></svg></div>
            <div><div class="feat-nav-text-title">CRM & Integrations</div><div class="feat-nav-text-desc">Marx, Wapix, Payment & logistics APIs</div></div>
          </div>
          <div class="feat-nav-item" data-panel="templates">
            <div class="feat-nav-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg></div>
            <div><div class="feat-nav-text-title">Website Templates</div><div class="feat-nav-text-desc">2 layout styles, fully brand-customizable</div></div>
          </div>
        </div>
      </div>

      <div class="feat-preview-panel">
        <div class="feat-preview-card">

          <div class="feat-panel active" id="panel-delivery">
            <div class="panel-delivery" style="flex:1;display:flex;flex-direction:column;gap:14px">
              <div>
                <div class="panel-top-bar" style="margin:-28px -28px 16px;padding:14px 20px;background:var(--o);display:flex;align-items:center;justify-content:space-between">
                  <span style="font-family:'Bricolage Grotesque',sans-serif;font-size:14px;font-weight:800;color:#fff">Delivery Management</span>
                  <span class="panel-live-badge" style="display:flex;align-items:center;gap:5px;font-size:11px;font-weight:600;color:rgba(255,255,255,.8)"><span class="panel-live-dot"></span>Live</span>
                </div>
                <div class="map-mock">
                  <div class="map-grid"></div>
                  <div class="map-pin map-pin1">🛵</div>
                  <div class="map-pin map-pin2">📍</div>
                  <div style="position:absolute;bottom:8px;left:8px;background:rgba(0,0,0,.6);color:#fff;font-size:9.5px;font-weight:600;padding:4px 8px;border-radius:6px;font-family:'DM Sans',sans-serif">3 drivers active · 8 orders en route</div>
                </div>
              </div>
              <div class="delivery-orders-list">
                <div class="do-item"><span class="do-num">#284</span><div class="do-info"><div class="do-name">James D. — Downtown</div><div class="do-detail">Pickup in 8 min</div></div><span class="do-status ob-orange">En Route</span></div>
                <div class="do-item"><span class="do-num">#283</span><div class="do-info"><div class="do-name">Sara K. — Marina</div><div class="do-detail">Delivered 3 min ago</div></div><span class="do-status ob-green">Done</span></div>
                <div class="do-item"><span class="do-num">#285</span><div class="do-info"><div class="do-name">Rami H. — Westside</div><div class="do-detail">Driver assigned</div></div><span class="do-status ob-blue">Dispatched</span></div>
              </div>
              <div style="background:var(--o-glow);border:1px solid var(--o-mid);border-radius:var(--r);padding:12px 14px;display:flex;align-items:center;gap:10px">
                <span style="font-size:18px">⚡</span>
                <div><div style="font-family:'Bricolage Grotesque',sans-serif;font-size:13px;font-weight:700;color:var(--o)">Auto-dispatch enabled</div><div style="font-size:11.5px;color:var(--muted)">Nearest available driver assigned instantly</div></div>
              </div>
            </div>
          </div>

          <div class="feat-panel" id="panel-payments">
            <div class="panel-payments" style="flex:1">
              <div style="margin-bottom:16px">
                <div style="font-family:'Bricolage Grotesque',sans-serif;font-size:16px;font-weight:800;color:var(--ink);margin-bottom:6px">Payment Overview</div>
                <div style="font-size:12.5px;color:var(--muted)">Today's transactions</div>
              </div>
              <div class="payment-cards">
                <div class="payment-card"><div class="payment-logo" style="color:var(--o)">MyFatoorah</div><div class="payment-status"><span>●</span> Connected</div><div class="payment-stat">8,420KD</div><div class="payment-stat-label">processed today</div></div>
                <div class="payment-card"><div class="payment-logo" style="color:#003087">Payzah</div><div class="payment-status"><span>●</span> Connected</div><div class="payment-stat">2,180KD</div><div class="payment-stat-label">processed today</div></div>
              </div>
              <div class="payment-tx-list">
                <div class="ptx"><div class="ptx-icon" style="background:var(--o-glow)">💳</div><div><div class="ptx-name">James D.</div><div class="ptx-method">Visa ···4929</div></div><div class="ptx-amount">+38.50KD</div></div>
                <div class="ptx"><div class="ptx-icon" style="background:#f0f0ff">🍎</div><div><div class="ptx-name">Sara K.</div><div class="ptx-method">Apple Pay</div></div><div class="ptx-amount">+52.00KD</div></div>
                <div class="ptx"><div class="ptx-icon" style="background:#fff4e6">🏦</div><div><div class="ptx-name">Refund — #271</div><div class="ptx-method">Original card</div></div><div class="ptx-amount neg">-14.50KD</div></div>
                <div class="ptx"><div class="ptx-icon" style="background:#e8f8f0">💚</div><div><div class="ptx-name">Mohamed R.</div><div class="ptx-method">Google Pay</div></div><div class="ptx-amount">+29.00KD</div></div>
              </div>
            </div>
          </div>

          <div class="feat-panel" id="panel-reports">
            <div class="panel-reports" style="flex:1">
              <div style="font-family:'Bricolage Grotesque',sans-serif;font-size:16px;font-weight:800;color:var(--ink);margin-bottom:4px">Business Insights</div>
              <div style="font-size:12.5px;color:var(--muted);margin-bottom:16px">Last 30 days performance</div>
              <div class="reports-chart-wrap">
                <div class="rcw-header"><span class="rcw-title">Monthly Revenue</span><span class="rcw-badge">↑ 28.4%</span></div>
                <div class="big-bars">
                  <div class="bb" style="height:40%;animation-delay:.0s"></div>
                  <div class="bb" style="height:55%;animation-delay:.05s"></div>
                  <div class="bb" style="height:48%;animation-delay:.10s"></div>
                  <div class="bb hi" style="height:72%;animation-delay:.15s"></div>
                  <div class="bb hi" style="height:80%;animation-delay:.20s"></div>
                  <div class="bb hi" style="height:90%;animation-delay:.25s"></div>
                  <div class="bb hi" style="height:100%;animation-delay:.30s"></div>
                </div>
              </div>
              <div class="reports-metrics">
                <div class="rm-card"><div class="rm-val">84000KD</div><div class="rm-label">Total Revenue</div></div>
                <div class="rm-card"><div class="rm-val">1,842</div><div class="rm-label">Orders</div></div>
                <div class="rm-card"><div class="rm-val">4.8★</div><div class="rm-label">Avg. Rating</div></div>
                <div class="rm-card"><div class="rm-val">34%</div><div class="rm-label">Repeat Rate</div></div>
                <div class="rm-card"><div class="rm-val">46KD</div><div class="rm-label">Avg. Order</div></div>
                <div class="rm-card"><div class="rm-val">612</div><div class="rm-label">New Customers</div></div>
              </div>
            </div>
          </div>

          <div class="feat-panel" id="panel-promotions">
            <div class="panel-promotions" style="flex:1">
              <div style="font-family:'Bricolage Grotesque',sans-serif;font-size:16px;font-weight:800;color:var(--ink);margin-bottom:4px">Active Promotions</div>
              <div style="font-size:12.5px;color:var(--muted);margin-bottom:16px">Promotion performance this week</div>
              <div class="promo-cards">
                <div class="promo-card">
                  <div class="promo-header"><span class="promo-title">FIRSTORDER20 — 20% Off</span><span class="promo-status ob-green">Active</span></div>
                  <div class="promo-bar-wrap"><div class="promo-bar" style="width:78%"></div></div>
                  <div class="promo-stats"><div><div class="promo-stat-lbl">Used</div><div class="promo-stat-val">234</div></div><div><div class="promo-stat-lbl">Revenue</div><div class="promo-stat-val">4,820KD</div></div><div><div class="promo-stat-lbl">Conv.</div><div class="promo-stat-val">78%</div></div></div>
                </div>
                <div class="promo-card">
                  <div class="promo-header"><span class="promo-title">LUNCH15 — 15% 11a-2p</span><span class="promo-status ob-orange">Scheduled</span></div>
                  <div class="promo-bar-wrap"><div class="promo-bar" style="width:45%;animation-delay:.3s"></div></div>
                  <div class="promo-stats"><div><div class="promo-stat-lbl">Used</div><div class="promo-stat-val">112</div></div><div><div class="promo-stat-lbl">Revenue</div><div class="promo-stat-val">2,190KD</div></div><div><div class="promo-stat-lbl">Conv.</div><div class="promo-stat-val">45%</div></div></div>
                </div>
              </div>
              <div style="background:var(--o-glow);border:1px solid var(--o-mid);border-radius:var(--r);padding:12px 14px">
                <div style="font-size:11px;font-weight:700;color:var(--o);margin-bottom:5px">💡 AI Recommendation</div>
                <div style="font-size:12px;color:var(--body)">Launch a weekend promotion — your Saturday orders are 40% below peak potential.</div>
              </div>
            </div>
          </div>

          <div class="feat-panel" id="panel-crm">
            <div class="panel-crm" style="flex:1">
              <div style="font-family:'Bricolage Grotesque',sans-serif;font-size:16px;font-weight:800;color:var(--ink);margin-bottom:4px">Connected Integrations</div>
              <div style="font-size:12.5px;color:var(--muted);margin-bottom:16px">Your existing tools, fully synced</div>
              <div class="crm-integration-grid">
                <div class="crm-int-card"><div class="crm-int-ico" style="background:#fff1f3">🔴</div><div><div class="crm-int-name">Marx CRM</div><div class="crm-int-type">Customer data</div><div class="crm-connected">Connected</div></div></div>
                <div class="crm-int-card"><div class="crm-int-ico" style="background:#fff8f0">📧</div><div><div class="crm-int-name">Whapex</div><div class="crm-int-type">Shared Chat inbox</div><div class="crm-connected">Connected</div></div></div>
                <div class="crm-int-card"><div class="crm-int-ico" style="background:#f0fff4">🔗</div><div><div class="crm-int-name">Payment & Logistics API</div><div class="crm-int-type">Custom builds</div><div class="crm-connected">Active</div></div></div>
              </div>
              <div class="crm-activity">
                <div class="crm-act"><span class="crm-act-dot" style="background:var(--o)"></span><span class="crm-act-text">New customer synced to Marx</span><span class="crm-act-time">2s ago</span></div>
                <div class="crm-act"><span class="crm-act-dot" style="background:#16a34a"></span><span class="crm-act-text">Message received in Whapex</span><span class="crm-act-time">4s ago</span></div>
              </div>
            </div>
          </div>

           <div class="feat-panel" id="panel-templates">
            <div class="panel-templates" style="flex:1">
              <div style="font-family:'Bricolage Grotesque',sans-serif;font-size:16px;font-weight:800;color:var(--ink);margin-bottom:4px">Website Templates</div>
              <div style="font-size:12.5px;color:var(--muted);margin-bottom:16px">2 layouts · Fully brand-customizable</div>
              
              <div class="tmpl-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                
                <div class="tmpl-card active">
                  <div class="tmpl-topbar" style="background:#fff"><div class="tmpl-topbar-dot"></div></div>
                  <div class="tmpl-body" style="background:#fafafa; padding: 4px;">
                    <div class="tmpl-hero-blk" style="background:linear-gradient(135deg,var(--o),var(--o-mid)); height: 100%; width: 100%; border-radius: 2px;"></div>
                  </div>
                  <div class="tmpl-label">Full Screen</div>
                </div>

                <div class="tmpl-card">
                  <div class="tmpl-topbar" style="background:#fff"><div class="tmpl-topbar-dot"></div></div>
                  <div class="tmpl-body" style="background:#fafafa; display: flex; gap: 4px; padding: 4px;">
                    <div style="flex: 1; background: var(--o); border-radius: 2px;"></div>
                    <div style="flex: 1; background: #e5e5e5; border-radius: 2px;"></div>
                  </div>
                  <div class="tmpl-label">Split Screen</div>
                </div>

              </div>

              <div class="tmpl-customize">
                <div>
                  <div class="tmpl-cust-text">Customize brand colors</div>
                  <div style="font-size:11px;color:var(--muted);margin-top:2px">Fonts, banners, menu layout, offers</div>
                </div>
                <div class="tmpl-cust-dots">
                  <div class="tmpl-cust-dot" style="background:var(--o)"></div>
                  <div class="tmpl-cust-dot" style="background:#3b82f6"></div>
                  <div class="tmpl-cust-dot" style="background:#16a34a"></div>
                  <div class="tmpl-cust-dot" style="background:var(--ink)"></div>
                </div>
              </div>
            </div>
          </div>
          

        </div>
      </div>
    </div>
  </div>
</section> -->

<!-- ══════════ SHOWCASE ══════════ -->
<!-- <section id="showcase">
  <div class="container">
    <div class="content-gap fade-up" style="text-align:center">
      <span class="eyebrow"><span class="eyebrow-dot"></span>Dashboard Preview</span>
      <h2 class="heading-lg" style="margin-top:16px;margin-bottom:16px">Complete visibility.<br/>Zero blind spots.</h2>
      <p class="subtext" style="margin:0 auto">Your entire operation, visible in one unified dashboard — live orders, revenue, analytics, and customer insights in real time.</p>
    </div>
    <div class="showcase-grid">
      <div>
        <div class="showcase-points">
          <div class="showcase-point fade-up">
            <div class="sp-num">1</div>
            <div><div class="sp-title">Live order feed across all branches</div><div class="sp-desc">Watch every incoming order appear in real time. Accept, assign, and track without switching tabs or tools.</div></div>
          </div>
          <div class="showcase-point fade-up d1">
            <div class="sp-num">2</div>
            <div><div class="sp-title">Revenue & performance analytics</div><div class="sp-desc">Daily, weekly, and monthly revenue charts. Understand peak hours, top dishes, and conversion rates at a glance.</div></div>
          </div>
          <div class="showcase-point fade-up d2">
            <div class="sp-num">3</div>
            <div><div class="sp-title">Customer behavior insights</div><div class="sp-desc">Track returning customer rate, average order value, and lifetime spend to make smarter marketing decisions.</div></div>
          </div>
          <div class="showcase-point fade-up d3">
            <div class="sp-num">4</div>
            <div><div class="sp-title">Promotion performance tracking</div><div class="sp-desc">See exactly which campaigns are driving revenue — and which aren't. Optimize in real time, not next week.</div></div>
          </div>
        </div>
      </div>
      <div class="fade-up d2">
        <div class="showcase-visual">
          <div class="sv-header">
            <span class="sv-title">Sara Analytics</span>
            <span class="sv-live-tag"><span class="panel-live-dot"></span>Live</span>
          </div>
          <div class="sv-body">
            <div class="sv-stats-row">
              <div class="sv-stat"><div class="sv-stat-val">54000KD</div><div class="sv-stat-lbl">Revenue</div></div>
              <div class="sv-stat"><div class="sv-stat-val">1.8K</div><div class="sv-stat-lbl">Orders</div></div>
              <div class="sv-stat"><div class="sv-stat-val">4.8★</div><div class="sv-stat-lbl">Rating</div></div>
            </div>
            <div class="sv-big-chart">
              <div class="sv-chart-row-top">
                <span class="sv-chart-title">Weekly Revenue — All Branches</span>
                <span class="sv-chart-delta">↑ 28.4%</span>
              </div>
              <div class="sv-line-chart">
                <svg viewBox="0 0 400 60" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                  <defs>
                    <linearGradient id="lineGrad" x1="0" y1="0" x2="0" y2="1">
                      <stop offset="0%" stop-color="#FF7A00" stop-opacity=".18"/>
                      <stop offset="100%" stop-color="#FF7A00" stop-opacity="0"/>
                    </linearGradient>
                  </defs>
                  <path d="M0,50 C40,42 80,38 120,30 C160,22 200,28 240,18 C280,8 320,14 360,6 L400,4 L400,60 L0,60 Z" fill="url(#lineGrad)"/>
                  <path d="M0,50 C40,42 80,38 120,30 C160,22 200,28 240,18 C280,8 320,14 360,6 L400,4" fill="none" stroke="#FF7A00" stroke-width="2" stroke-linecap="round"/>
                  <circle cx="360" cy="6" r="4" fill="#FF7A00"/>
                  <circle cx="360" cy="6" r="8" fill="none" stroke="#FF7A00" stroke-opacity=".3" stroke-width="1.5"/>
                </svg>
              </div>
            </div>
            <div class="sv-orders-list">
              <div class="sv-order">
                <div class="order-avatar" style="background:var(--o);width:26px;height:26px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:9.5px;font-weight:800;color:#fff;flex-shrink:0;font-family:'Bricolage Grotesque',sans-serif">AM</div>
                <div class="order-info" style="flex:1"><div class="order-name">Ahmed M. — Downtown</div><div class="order-detail">2 min ago</div></div>
                <span class="order-badge ob-orange">Preparing</span>
              </div>
              <div class="sv-order">
                <div class="order-avatar" style="background:#8b5cf6;width:26px;height:26px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:9.5px;font-weight:800;color:#fff;flex-shrink:0;font-family:'Bricolage Grotesque',sans-serif">LN</div>
                <div class="order-info" style="flex:1"><div class="order-name">Lena N. — Marina</div><div class="order-detail">5 min ago</div></div>
                <span class="order-badge ob-blue">Dispatched</span>
              </div>
              <div class="sv-order">
                <div class="order-avatar" style="background:#16a34a;width:26px;height:26px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:9.5px;font-weight:800;color:#fff;flex-shrink:0;font-family:'Bricolage Grotesque',sans-serif">RK</div>
                <div class="order-info" style="flex:1"><div class="order-name">Rami K. — Westside</div><div class="order-detail">8 min ago</div></div>
                <span class="order-badge ob-green">Delivered</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section> -->

<!-- ══════════ WHY ══════════ -->
<section id="why">
  <div class="why-glow1"></div>
  <div class="why-glow2"></div>
  <div class="container">
    <div class="why-inner">
      <div class="why-header fade-up">
        <span class="eyebrow" style="background:rgba(255,122,0,.14);border-color:rgba(255,122,0,.3);color:var(--o)"><span class="eyebrow-dot"></span>Why Platpilots?</span>
        <h2 class="heading-lg" style="color: #000 !important;margin-top:16px;margin-bottom:14px">Stop renting your customers.<br/><em style="color:var(--o);font-style:italic">Start owning them.</em></h2>
        <p class="subtext" style="margin:0 auto;text-align:center">Marketplace platforms take 15–30% of every order and keep your customer data.<br/>Platepilota changes that equation entirely.</p>
      </div>
      <div class="why-grid">
        <div class="why-card fade-up">
          <div class="why-card-num">01</div>
          <div class="why-card-icon">
            <x-lucide-user-round class="input-icon" />
            <!-- <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg> -->
          </div>
          <div class="why-card-title">Own your customer relationships</div>
          <div class="why-card-desc">Every customer who orders belongs to you — their email, history, preferences, and reorder patterns. No platform keeps them from you.</div>
        </div>
        <div class="why-card fade-up d1">
          <div class="why-card-num">02</div>
          <div class="why-card-icon">
            <x-lucide-vector-square class="input-icon" />
            <!-- <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg> -->
          </div>
          <div class="why-card-title">Fully Customizable & Data-Driven</div>
          <div class="why-card-desc">Unlike rigid platforms, PlatePilot adapts to how you operate. Customize workflows, settings, and features to match your business model—no compromises, no limitations.</div>
        </div>
        <div class="why-card fade-up d2">
          <div class="why-card-num">03</div>
          <div class="why-card-icon">
            <x-lucide-heart class="input-icon" />
            <!-- <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg> -->
          </div>
          <div class="why-card-title">Built Around Your Business</div>
          <div class="why-card-desc">One login for every location. Set menus, hours, delivery zones, and pricing per branch while monitoring everything from one view.</div>
        </div>
        <div class="why-card fade-up d3">
          <div class="why-card-num">04</div>
          <div class="why-card-icon">
            <x-lucide-code class="input-icon" />
            <!-- <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg> -->
          </div>
          <div class="why-card-title">Launch without dev complexity</div>
          <div class="why-card-desc">No engineering team needed. Set up your menu, connect payments, and go live in under 48 hours with guided onboarding and templates.</div>
        </div>
        <div class="why-card fade-up d4">
          <div class="why-card-num">05</div>
          <div class="why-card-icon">
             <x-lucide-house class="input-icon" />
            <!-- <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg> -->
          </div>
          <div class="why-card-title">Reduce marketplace dependency</div>
          <div class="why-card-desc">Build a sustainable, owned ordering channel. Your brand, your rules, your pricing — not dictated by an algorithm you don't control.</div>
        </div>
        <div class="why-card fade-up d5">
          <div class="why-card-num">06</div>
          <div class="why-card-icon">
             <x-lucide-trending-up class="input-icon" />
            <!-- <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg> -->
          </div>
          <div class="why-card-title">Start Lean, Scale Without Limits</div>
          <div class="why-card-desc">Get started with low upfront costs and scale as you grow. PlatePilot is designed to support businesses at every stage without locking you into expensive or restrictive plans.</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════ TEMPLATES ══════════ -->
<!-- <section id="templates">
  <div class="container">
    <div class="content-gap fade-up" style="text-align:center">
      <span class="eyebrow"><span class="eyebrow-dot"></span>Website Templates</span>
      <h2 class="heading-lg" style="margin-top:16px;margin-bottom:16px">A layout for every<br/>brand and concept</h2>
      <p class="subtext" style="margin:0 auto">Choose a professionally designed template and make it yours — brand colors, menu structure, banners, offers, and content blocks. No code required.</p>
    </div>
    
    <div class="templates-scroll" style="display:flex; justify-content:center; gap:16px; flex-wrap:wrap;">
  
      <div class="t-card fade-up" style="width: 20%;">
        <div class="t-card-preview">
          <div class="t-card-topbar" style="background:#fafafa"><div class="t-card-topbar-dot" style="background:#ddd"></div></div>
          <div class="t-card-body" style="background:#fafafa; padding: 12px;">
            <div class="t-hero-block" style="background:linear-gradient(135deg,var(--o),var(--o-mid)); height: 100%; border-radius: 4px; margin: 0;"></div>
          </div>
        </div>
        <div class="t-card-footer">
          <div class="t-card-name">Full Screen</div>
          <div class="t-card-note">Immersive & cinematic</div>
        </div>
      </div>

      <div class="t-card fade-up d1" style="width: 20%;">
        <div class="t-card-preview">
          <div class="t-card-topbar" style="background:#fafafa"><div class="t-card-topbar-dot" style="background:#ddd"></div></div>
          <div class="t-card-body" style="background:#fafafa; display: flex; gap: 8px; padding: 12px;">
            <div style="flex: 1; background: linear-gradient(135deg,var(--o),var(--o-mid)); border-radius: 4px;"></div>
            <div style="flex: 1; background: #eee; border-radius: 4px;"></div>
          </div>
        </div>
        <div class="t-card-footer">
          <div class="t-card-name">Split Screen</div>
          <div class="t-card-note">Balanced & modern</div>
        </div>
      </div>

    </div>
  </div>
</section> -->

<!-- ══════════ INTEGRATIONS ══════════ -->
 <section id="integrations">
  <div class="container">
    <div class="integrations-header fade-up">
      <!-- <div class="section-label">Integrations</div> -->
       <span class="eyebrow" style="background:rgba(255,122,0,.14);border-color:rgba(255,122,0,.3);color:var(--o)"><span class="eyebrow-dot"></span>Integrations</span>
      <h2>Connects to the tools you already use</h2>
      <p>Plug Orderly into your existing workflow with ready-made integrations.</p>
    </div>
    <div class="integrations-categories fade-up delay-1">
      <div class="int-cat active">All</div>
      <div class="int-cat">Payments</div>
      <div class="int-cat">Logistics</div>
      <div class="int-cat">CRM</div>
      <!-- <div class="int-cat">Marketing</div>
      <div class="int-cat">Analytics</div>
      <div class="int-cat">Operations</div> -->
    </div>
    <div class="integrations-grid fade-up delay-2">
      <div class="int-card"  data-cat="Payments"><div class="int-icon">💳</div><div class="int-name">Payzah</div><div class="int-cat-label">Payments</div></div>
      <div class="int-card"  data-cat="Payments"><div class="int-icon">🔵</div><div class="int-name">MyFatoorah</div><div class="int-cat-label">Payments</div></div>
      <div class="int-card"  data-cat="Payments"><div class="int-icon">🌐</div><div class="int-name">KNET</div><div class="int-cat-label">Payments</div></div>
      <div class="int-card"  data-cat="Logistics"><div class="int-icon">🚚</div><div class="int-name">Aramex</div><div class="int-cat-label">Logistics</div></div>
      <div class="int-card" data-cat="Logistics"><div class="int-icon">📦</div><div class="int-name">Armada</div><div class="int-cat-label">Logistics</div></div>
      <div class="int-card" data-cat="CRM"><div class="int-icon">💬</div><div class="int-name">Whapex</div><div class="int-cat-label">CRM</div></div>
      <div class="int-card" data-cat="CRM"><div class="int-icon">📊</div><div class="int-name">Marx</div><div class="int-cat-label">CRM</div></div>
    </div>
    <!-- <div style="text-align:center;margin-top:32px" class="fade-up delay-3">
      <a href="#" style="font-size:14px;font-weight:600;color:var(--orange);border-bottom:1.5px solid rgba(255,122,0,0.3);padding-bottom:2px">View all 40+ integrations →</a>
    </div> -->
  </div>
</section>
<!-- <section id="integrations">
  <div class="container">
    <div class="content-gap fade-up" style="text-align:center">
      <span class="eyebrow"><span class="eyebrow-dot"></span>Integrations</span>
      <h2 class="heading-lg" style="margin-top:16px;margin-bottom:16px">Connects with the tools<br/>you already use</h2>
      <p class="subtext" style="margin:0 auto">Plug in your payments, logistics, marketing, and CRM stack — without switching platforms or rebuilding workflows.</p>
    </div>
    <div class="int-categories">
      <div class="fade-up">
        <div class="int-category-label">💳 Payments</div>
        <div class="int-cards-row">
          <div class="int-card"><span class="int-card-ico">💳</span><div><div class="int-card-name">Stripe</div><div class="int-card-type">Cards & wallets</div></div></div>
          <div class="int-card"><span class="int-card-ico">🅿️</span><div><div class="int-card-name">PayPal</div><div class="int-card-type">Global payments</div></div></div>
          <div class="int-card"><span class="int-card-ico">🟠</span><div><div class="int-card-name">Tap Payments</div><div class="int-card-type">MENA gateway</div></div></div>
          <div class="int-card"><span class="int-card-ico">🍎</span><div><div class="int-card-name">Apple Pay</div><div class="int-card-type">Digital wallet</div></div></div>
          <div class="int-card"><span class="int-card-ico">🟡</span><div><div class="int-card-name">Moyasar</div><div class="int-card-type">KSA gateway</div></div></div>
        </div>
      </div>
      <div class="fade-up d1">
        <div class="int-category-label">🛵 Logistics</div>
        <div class="int-cards-row">
          <div class="int-card"><span class="int-card-ico">🚀</span><div><div class="int-card-name">Fetchr</div><div class="int-card-type">Last-mile delivery</div></div></div>
          <div class="int-card"><span class="int-card-ico">🛵</span><div><div class="int-card-name">Lalamove</div><div class="int-card-type">On-demand drivers</div></div></div>
          <div class="int-card"><span class="int-card-ico">📦</span><div><div class="int-card-name">Bosta</div><div class="int-card-type">Egypt & KSA</div></div></div>
          <div class="int-card"><span class="int-card-ico">🗺️</span><div><div class="int-card-name">Google Maps</div><div class="int-card-type">Tracking & zones</div></div></div>
        </div>
      </div>
      <div class="fade-up d2">
        <div class="int-category-label">📣 CRM & Marketing</div>
        <div class="int-cards-row">
          <div class="int-card"><span class="int-card-ico">🔴</span><div><div class="int-card-name">HubSpot</div><div class="int-card-type">CRM & pipelines</div></div></div>
          <div class="int-card"><span class="int-card-ico">📧</span><div><div class="int-card-name">Mailchimp</div><div class="int-card-type">Email campaigns</div></div></div>
          <div class="int-card"><span class="int-card-ico">📲</span><div><div class="int-card-name">WhatsApp Business</div><div class="int-card-type">Notifications</div></div></div>
          <div class="int-card"><span class="int-card-ico">📘</span><div><div class="int-card-name">Meta Pixel</div><div class="int-card-type">Ad retargeting</div></div></div>
          <div class="int-card"><span class="int-card-ico">📈</span><div><div class="int-card-name">Google Analytics</div><div class="int-card-type">Traffic & events</div></div></div>
        </div>
      </div>
      <div class="fade-up d3">
        <div class="int-category-label">⚙️ Operations</div>
        <div class="int-cards-row">
          <div class="int-card"><span class="int-card-ico">⚡</span><div><div class="int-card-name">Zapier</div><div class="int-card-type">Automations</div></div></div>
          <div class="int-card"><span class="int-card-ico">🖨️</span><div><div class="int-card-name">Star Printers</div><div class="int-card-type">Kitchen receipts</div></div></div>
          <div class="int-card"><span class="int-card-ico">🏪</span><div><div class="int-card-name">Square POS</div><div class="int-card-type">Point of sale</div></div></div>
          <div class="int-card"><span class="int-card-ico">🔗</span><div><div class="int-card-name">REST API</div><div class="int-card-type">Custom builds</div></div></div>
        </div>
      </div>
    </div>
  </div>
</section> -->

<!-- ══════════ HOW IT WORKS ══════════ -->
<section id="how">
  <div class="container">
    <div class="content-gap fade-up" style="text-align:center">
      <span class="eyebrow"><span class="eyebrow-dot"></span>How It Works</span>
      <h2 class="heading-lg" style="margin-top:16px;margin-bottom:16px">Live in 3 steps.<br/>Not 3 months.</h2>
      <p class="subtext" style="margin:0 auto">From sign-up to accepting your first direct order in under 48 hours. Guided setup, no engineering team required.</p>
    </div>
    <div class="how-steps">
      <div class="how-step fade-up">
        <div class="how-step-ball">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="40" height="40"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
        </div>
        <div class="how-step-num">Step 01</div>
        <div class="how-step-title">Set Up Menu & Branches</div>
        <div class="how-step-desc">Add your menu, categories, pricing, and photos. Set up branch locations, delivery zones, and hours in a guided wizard.</div>
      </div>
      <div class="how-step fade-up d2">
        <div class="how-step-ball">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" width="40" height="40"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
        </div>
        <div class="how-step-num">Step 02</div>
        <div class="how-step-title">Connect Payments & Delivery</div>
        <div class="how-step-desc">Plug in your preferred payment gateway and delivery partner. Set commission rules, fees, and payout schedules — one click.</div>
      </div>
      <div class="how-step fade-up d4">
        <div class="how-step-ball">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" width="40" height="40"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <div class="how-step-num">Step 03</div>
        <div class="how-step-title">Start Receiving Direct Orders</div>
        <div class="how-step-desc">Share your ordering link or embed it anywhere. Customers order directly — and you keep every cent with 0% commission.</div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════ PRICING ══════════ -->
<section id="pricing">
  <div class="container">
    <div class="content-gap fade-up" style="text-align:center">
      <span class="eyebrow"><span class="eyebrow-dot"></span>Pricing</span>
      <h2 class="heading-lg" style="margin-top:16px;margin-bottom:16px">Flat monthly pricing.<br/>0% commission.</h2>
      <p class="subtext" style="margin:0 auto">No hidden fees. No per-order cuts. No long-term contracts. Just one predictable rate that grows with you.</p>
    </div>
    <div class="pricing-grid">
      <div class="price-card fade-up">
        <div class="price-tier-name">Starter</div>
        <div class="price-tagline">For getting your business online</div>
        <!-- <div class="price-amount"><span class="price-dollar">$</span>49<span class="price-period">/mo</span></div> -->
        <div class="price-amount">
          250
          <span class="price-dollar">KD</span>
          <span class="price-period">/yr</span></div>
        <div class="price-divider"></div>
        <ul class="price-features-list">
          <li><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>www.yourdomain.com</li>
          <li><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>Reports & Analytics</li>
          <li><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>Multiple Delivery Partners</li>
          <li><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>Payment Gateway Options</li>
          <li><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>Dedicated Support</li>
          <li><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>Advanced Product Management</li>
          <li><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>Multiple Layouts</li>
          <li><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>Auto Receive & Dispatch</li>
          <li><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>Marketing & Smart Promotions</li>
        </ul>
        <a href="#contact" class="btn btn-outline-full btn-full">Contact Sales</a>
      </div>
      <div class="price-card featured fade-up d1">
        <div class="price-rec-badge">Recommended</div>
        <div class="price-tier-name">Growth</div>
        <div class="price-tagline">Scale your sales, marketing, and performance</div>
        <div class="price-amount">
         450
          <span class="price-dollar">
            KD
          </span>
         <span class="price-period">/yr</span></div>
        <div class="price-divider"></div>
        <ul class="price-features-list">
          <li><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>Modifiers</li>
          <li><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>Cross Selling</li>
          <li><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>Popup Banners</li>
          <li><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>Inventory Management</li>
          <li><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>Loyalty Points & Wallets</li>
          <li><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>Extra Product Fields</li>
          <li style="display: none;"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>Customer Can Upload Files</li>
          <li style="display: none;"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>White Label</li>
        </ul>
        <a href="#contact" class="btn btn-white btn-full">Contact Sales</a>
      </div>
      <div class="price-card fade-up d2">
        <div class="price-tier-name">Enterprise</div>
        <div class="price-tagline">Advanced multi-branch ops, SSO, and custom API</div>
        <div class="price-amount" style="font-size:36px;align-items:center;padding-top:8px">Custom</div>
        <div class="price-divider"></div>
        <ul class="price-features-list">
          <li><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>Unlimited branches</li>
          <li><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>Everything in Growth</li>
          <li><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>Custom API access</li>
          <li><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>SSO & advanced security</li>
          <li><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>White-label option</li>
          <li><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>Dedicated account manager</li>
        </ul>
        <a href="#contact" class="btn btn-primary btn-full">Contact Sales</a>
      </div>
    </div>
    <!-- <p style="text-align:center;font-size:13px;color:var(--muted);margin-top:24px">All plans include 14-day free trial · 0% commission on all orders · Cancel anytime</p> -->
  </div>
</section>

<!-- ══════════ TESTIMONIALS ══════════ -->
<section id="testimonials">
  <div class="container">
    <div class="content-gap fade-up" style="text-align:center">
      <span class="eyebrow"><span class="eyebrow-dot"></span>Customer Stories</span>
      <h2 class="heading-lg" style="margin-top:16px;margin-bottom:16px">Trusted by the businesses<br/>that left marketplaces behind</h2>
    </div>
    <div class="testi-grid">
      <div class="testi-card fade-up">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-quote">"We launched our own ordering site in two days. Within 6 weeks, 45% of our orders came directly through our platform. The commission savings funded our entire next expansion."</p>
        <div class="testi-divider"></div>
        <div class="testi-author">
          <div class="testi-avatar" style="background:linear-gradient(135deg,var(--o),var(--o-d))">KA</div>
          <div><div class="testi-name">Khalid Al-Rashidi</div><div class="testi-role">Owner · Al-Rashidi Burgers, Riyadh</div></div>
        </div>
      </div>
      <div class="testi-card fade-up d1">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-quote">"Running a cloud kitchen means margins are everything. Platpilot gave us a direct channel with zero commission, real-time kitchen management, and analytics that actually helped us cut waste."</p>
        <div class="testi-divider"></div>
        <div class="testi-author">
          <div class="testi-avatar" style="background:linear-gradient(135deg,#8b5cf6,#6d28d9)">SP</div>
          <div><div class="testi-name">Sara</div><div class="testi-role">Founder · CloudX Kitchen, Dubai</div></div>
        </div>
      </div>
      <div class="testi-card fade-up d2">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-quote">"Managing 14 branches used to require 3 separate tools and a full-time coordinator. Now one person handles everything from one dashboard. The multi-branch reporting alone changed how we make decisions."</p>
        <div class="testi-divider"></div>
        <div class="testi-author">
          <div class="testi-avatar" style="background:linear-gradient(135deg,#0ea5e9,#0284c7)">MH</div>
          <div><div class="testi-name">Mohamed Hassan</div><div class="testi-role">VP Operations · TableOne Group, Cairo</div></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════ FINAL CTA ══════════ -->
<section id="cta">
  <div class="cta-glow-bg"></div>
  <div class="cta-noise"></div>
  <div class="cta-orb1"></div>
  <div class="cta-orb2"></div>
  <div class="container">
    <div class="cta-inner fade-up">
      <h2 class="cta-h2">Ready to Own<br/>Your Online Orders?</h2>
      <p class="cta-sub">Join 2,400+ restaurants that stopped paying marketplace commissions and started building their own direct ordering channel.</p>
      <div class="cta-actions">
        <a href="#contact" class="btn" style="background:#fff;color:var(--o);font-size:16px;padding:16px 34px;font-weight:700">
          <x-lucide-circle-user class="input-icon" />
          <!-- <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" width="17" height="17"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> -->
          Contact Sales
        </a>
        <!-- <a href="#features" class="btn" style="background:rgba(255,255,255,.12);color:#fff;border:1.5px solid rgba(255,255,255,.25);font-size:16px;padding:16px 34px">
          Explore Features →
        </a> -->
      </div>
      <!-- <p class="cta-note">14-day free trial · 0% commission · No card required · Cancel anytime</p> -->
    </div>
  </div>
</section>

<!-- ══════════ CONTACT ══════════ -->
<section id="contact">
  <div class="container">
    <div class="contact-grid">
      <div class="fade-up">
        <span class="eyebrow"><span class="eyebrow-dot"></span>Get in Touch</span>
        <h2 class="heading-lg" style="margin-top:16px;margin-bottom:14px;font-size:clamp(28px,4vw,44px)">Let's get your<br/>platform live.</h2>
        <p class="subtext" style="margin-bottom:32px">Whether you need a walkthrough, enterprise pricing, or just have questions — we respond within 2 hours.</p>
        <div class="contact-info">
          <div class="contact-info-item">
            <div class="ci-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></div>
            <div><div class="ci-label">Email</div><div class="ci-value">support.platepilot@gmail.com</div></div>
          </div>
          <div class="contact-info-item">
            <div class="ci-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 014.18 9.81a19.79 19.79 0 01-3.07-8.67A2 2 0 013.11 1h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L7.09 8.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0121 16z"/></svg></div>
            <div><div class="ci-label">Phone</div><div class="ci-value">+965 9773 8123 </div></div>
          </div>
          <div class="contact-info-item">
            <div class="ci-icon"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
            <div><div class="ci-label">Headquarters</div><div class="ci-value">Sharq, Al-Asimah, Kuwait</div></div>
          </div>
        </div>
      </div>
      <div class="fade-up d2">
        <div class="contact-form-card">
          <div class="form-title">Request an audience</div>
          <div class="form-body">
            <div class="form-row">
              <div class="form-group"><label class="form-label">First Name</label><input class="form-input" type="text" placeholder="Khalid"/></div>
              <div class="form-group"><label class="form-label">Last Name</label><input class="form-input" type="text" placeholder="Hassan"/></div>
            </div>
            <div class="form-group"><label class="form-label">Business Email</label><input class="form-input" type="email" placeholder="you@restaurant.com"/></div>
            <div class="form-group"><label class="form-label">Business Name</label><input class="form-input" type="text" placeholder="Your restaurant or brand"/></div>
            <div class="form-group">
              <label class="form-label">Business Type</label>
              <select class="form-select">
                <option>Restaurant</option>
                <option>Cloud Kitchen</option>
                <option>Café</option>
                <option>Multi-Branch Chain</option>
                <option>Retail Food Brand</option>
              </select>
            </div>
            <div class="form-group"><label class="form-label">Message</label><textarea class="form-textarea" placeholder="Tell us about your ordering needs…"></textarea></div>
            <button class="btn btn-primary" style="width:100%;justify-content:center;font-size:15px;padding:14px" id="submitBtn">
              <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" width="16" height="16"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
              Submit
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════ FOOTER ══════════ -->
<footer>
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <a href="/">
            <img src="{{ asset('/themes/default/Footer_logo.png') }}" width="170">
        </a>
        <!-- <a href="#" class="footer-logo">
          <div class="footer-logo-mark">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" width="14" height="14"><circle cx="12" cy="12" r="9"/><path d="M12 8v4l2.5 2.5"/></svg>
          </div>
          OrderFlow
        </a> -->
        <p class="footer-tagline">The complete online ordering platform for restaurants, cafés, cloud kitchens, and multi-branch food brands worldwide.</p>
        <div class="footer-newsletter">
          <input type="email" class="footer-email-input" placeholder="Email for updates"/>
          <button class="footer-subscribe-btn">Subscribe</button>
        </div>
      </div>
      <div>
        <div class="footer-col-title">Product</div>
        <ul class="footer-links">
          <li><a href="#">Online Ordering</a></li>
          <li><a href="#">Dashboard</a></li>
          <li><a href="#">Analytics</a></li>
          <li><a href="#">Templates</a></li>
          <li><a href="#">Integrations</a></li>
          <li><a href="#">API Docs</a></li>
        </ul>
      </div>
      <div>
        <div class="footer-col-title">Solutions</div>
        <ul class="footer-links">
          <li><a href="#">Restaurants</a></li>
          <li><a href="#">Cloud Kitchens</a></li>
          <li><a href="#">Cafés</a></li>
          <li><a href="#">Multi-Branch</a></li>
          <li><a href="#">Enterprise</a></li>
          <li><a href="#">Retail Food</a></li>
        </ul>
      </div>
      <div>
        <div class="footer-col-title">Company</div>
        <ul class="footer-links">
          <li><a href="#">About</a></li>
          <li><a href="#">Blog</a></li>
          <li><a href="#">Careers</a></li>
          <li><a href="#">Press</a></li>
          <li><a href="#">Partners</a></li>
          <li><a href="#">Contact</a></li>
        </ul>
      </div>
      <div>
        <div class="footer-col-title">Legal</div>
        <ul class="footer-links">
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">Terms of Service</a></li>
          <li><a href="#">Cookie Policy</a></li>
          <li><a href="#">Security</a></li>
          <li><a href="#">GDPR</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span class="footer-copy">© 2026 Platepilot Technologies, Inc. All rights reserved.</span>
      <div class="footer-socials">
        <a href="#" class="social-icon"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>
        <a href="#" class="social-icon"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/></svg></a>
        <a href="#" class="social-icon"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738a.36.36 0 01.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.632-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24 12.017 24c6.624 0 11.99-5.373 11.99-12C24.007 5.367 18.641 0 12.017 0z"/></svg></a>
        <a href="#" class="social-icon"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
      </div>
    </div>
  </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', () => {
  /* ── NAV SCROLL EFFECT ── */
  const nav = document.getElementById('nav');
  window.addEventListener('scroll', () => {
    if (window.scrollY > 20) {
      nav.classList.add('shadow');
    } else {
      nav.classList.remove('shadow');
    }
  });

  /* ── FEATURES INTERACTIVITY ── */
  const navItems = document.querySelectorAll('.feature-nav-item');
  const panels = document.querySelectorAll('.feature-panel');

  navItems.forEach(item => {
    item.addEventListener('click', () => {
      const targetPanelId = item.getAttribute('data-panel');

      // 1. Update Navigation UI
      navItems.forEach(nav => nav.classList.remove('active'));
      item.classList.add('active');

      // 2. Update Content Panels
      panels.forEach(panel => {
        panel.classList.remove('active');
        // Check if the panel ID matches (handles 'integrations' vs 'integrations-feat' naming)
        if (panel.id === `panel-${targetPanelId}` || panel.id === `panel-${targetPanelId}-feat`) {
          panel.classList.add('active');
        }
      });
    });
  });

  /* ── FADE-IN ANIMATION OBSERVER ── */
  const fadeObserverOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
  };

  const fadeObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        // Once visible, no need to observe anymore
        fadeObserver.unobserve(entry.target);
      }
    });
  }, fadeObserverOptions);

  document.querySelectorAll('.fade-up').forEach(el => {
    fadeObserver.observe(el);
  });

  /* ── DYNAMIC BAR ANIMATION (RE-RUN ON TAB SWITCH) ── */
  // This triggers the CSS animations for the analytics bars when that tab is clicked
  const analyticsTab = document.querySelector('[data-panel="analytics"]');
  if (analyticsTab) {
    analyticsTab.addEventListener('click', () => {
      const bars = document.querySelectorAll('.mb, .perf-bar-fill');
      bars.forEach(bar => {
        bar.style.animation = 'none';
        bar.offsetHeight; // trigger reflow
        bar.style.animation = null;
      });
    });
  }
});


document.addEventListener("DOMContentLoaded", function () {
  const tabs = document.querySelectorAll(".int-cat");
  const cards = document.querySelectorAll(".int-card");

  tabs.forEach(tab => {
    tab.addEventListener("click", () => {
      // remove active state
      tabs.forEach(t => t.classList.remove("active"));
      tab.classList.add("active");

      const category = tab.textContent.trim();

      cards.forEach(card => {
        const cardCat = card.getAttribute("data-cat");

        if (category === "All" || cardCat === category) {
          card.style.display = "flex";
        } else {
          card.style.display = "none";
        }
      });
    });
  });
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
<script>
new Chart(document.getElementById('revenueChart'), {
  type: 'line',
  data: {
    labels: ['9a','10','11','12','1p','2','3','4','5'],
    datasets: [{
      data: [30, 48, 65, 98, 100, 82, 58, 73, 88],
      borderColor: '#FF7A00',
      borderWidth: 2,
      pointBackgroundColor: '#FF7A00',
      pointRadius: 3,
      fill: true,
      backgroundColor: 'rgba(255,122,0,0.08)',
      tension: 0.4
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ctx.parsed.y + '%' } } },
    scales: {
      x: { grid: { display: false }, border: { display: false }, ticks: { font: { size: 10 }, color: '#aaa' } },
      y: { display: false, min: 0, max: 115 }
    }
  }
});
</script>
</body>
</html>
