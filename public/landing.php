<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Open Your Savings Account | Commercial Bank of Ethiopia (Demo)</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Caveat:wght@600&display=swap" rel="stylesheet">
<style>
:root{--g:#0b3d3a;--g2:#0f5a52;--gold:#f5b82e;--gold2:#ffe9b0;--ink:#12302d;--mut:#5b6f6c;--bg:#f4f8f6;--card:#fff}
*{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:'Plus Jakarta Sans',system-ui,sans-serif;color:var(--ink);background:var(--bg);line-height:1.55}
a{color:inherit;text-decoration:none}
.wrap{max-width:1200px;margin:0 auto;padding:0 24px}
#bar{position:fixed;top:0;left:0;height:3px;width:0;background:var(--gold);z-index:100}
header{position:fixed;top:0;left:0;right:0;z-index:50;background:var(--g);color:#fff;transition:padding .3s,box-shadow .3s;padding:14px 0}
header.small{padding:7px 0;box-shadow:0 6px 24px #0005}
header .wrap{display:flex;align-items:center;justify-content:space-between;gap:20px}
.logo{display:flex;align-items:center;gap:12px}
.logo i{width:44px;height:44px;border-radius:50%;background:radial-gradient(circle at 30% 30%,#ffd76a,#b8860b);display:grid;place-items:center;font-style:normal;font-weight:800;color:var(--g);font-size:13px}
.logo b{display:block;font-size:15px}.logo small{color:var(--gold);font-size:11px}
nav{display:flex;gap:28px;font-size:14px;font-weight:500}
nav a{padding:6px 0;border-bottom:2px solid transparent;transition:.25s}
nav a:hover,nav a.on{border-color:var(--gold)}
.btn{display:inline-flex;align-items:center;gap:10px;padding:14px 26px;border-radius:40px;font-weight:600;font-size:15px;border:1px solid transparent;transition:transform .2s,box-shadow .2s;cursor:pointer;font-family:inherit}
.btn:hover{transform:translateY(-3px);box-shadow:0 10px 22px #0003}
.gold{background:var(--gold);color:var(--g)}
.dark{background:var(--g2);color:#fff}
.out{background:#fffc;border-color:var(--g);color:var(--g)}
header .btn{padding:10px 20px}
.hero{position:relative;min-height:600px;padding:150px 0 110px;overflow:hidden;background:linear-gradient(180deg,#fbe3bf 0%,#f6efe0 45%,#d6ead9 100%)}
.hero svg.sky{position:absolute;left:0;bottom:0;width:100%;height:60%}
.hero .wrap{position:relative;z-index:2}
.tag{display:inline-block;background:var(--gold2);border:1px solid #e9c564;padding:6px 16px;border-radius:30px;font-size:12px;font-weight:700;color:#7a5a05;margin-bottom:20px}
.hero h1{font-size:clamp(36px,5vw,58px);line-height:1.08;font-weight:800;max-width:620px;color:#0a2a27}
.hero p{max-width:480px;margin:20px 0 30px;font-size:17px;color:#223}
.hero .cta{display:flex;gap:16px;flex-wrap:wrap}
.script{position:absolute;right:6%;top:150px;font-family:'Caveat',cursive;font-size:40px;line-height:1;color:#12302d;transform:rotate(-8deg);animation:float 5s ease-in-out infinite;z-index:2}
.script:after{content:"";display:block;height:3px;width:130%;background:var(--gold);margin-top:8px;transform-origin:left;animation:draw 1.2s .8s both}
.person{position:absolute;right:16%;bottom:0;width:300px;height:470px;z-index:1;animation:rise 1.2s .3s both}
@keyframes float{50%{transform:rotate(-8deg) translateY(-12px)}}
@keyframes draw{from{transform:scaleX(0)}}
@keyframes rise{from{transform:translateY(60px);opacity:0}}
.hero h1,.hero p,.hero .cta,.tag{animation:in .8s both}
.tag{animation-delay:.1s}.hero h1{animation-delay:.25s}.hero p{animation-delay:.4s}.hero .cta{animation-delay:.55s}
@keyframes in{from{opacity:0;transform:translateY(26px)}}
.trust{background:#fff;padding:34px 0}
.trust .wrap{display:grid;grid-template-columns:repeat(4,1fr);gap:20px}
.t{display:flex;gap:16px;align-items:center;padding:0 12px;border-right:1px solid #e3eae8}
.t:last-child{border:0}
.ic{flex:none;width:56px;height:56px;border-radius:50%;background:#e4f1ed;display:grid;place-items:center;color:var(--g2);transition:transform .4s}
.t:hover .ic{transform:rotateY(180deg) scale(1.1)}
.ic svg{width:26px;height:26px;fill:none;stroke:currentColor;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
.t b{font-size:15px}.t span{display:block;font-size:13px;color:var(--mut)}
section{padding:90px 0}
.kick{font-size:13px;font-weight:700;color:#a07100;margin-bottom:8px}
h2{font-size:clamp(28px,3.4vw,40px);line-height:1.15;margin-bottom:12px}
.lead{color:var(--mut);max-width:560px}
.panel{background:#fff;border-radius:24px;padding:40px;box-shadow:0 12px 40px #0b3d3a14}
.grid2{display:grid;grid-template-columns:2fr 1fr;gap:28px;align-items:start}
.steps{display:grid;grid-template-columns:repeat(4,1fr);gap:26px;margin-top:34px;position:relative}
.steps:before{content:"";position:absolute;top:34px;left:8%;height:2px;width:0;background:repeating-linear-gradient(90deg,var(--gold) 0 8px,transparent 8px 16px);transition:width 1.6s .3s}
.steps.vis:before{width:84%}
.step .ic{width:68px;height:68px;position:relative;z-index:1;background:#e4f1ed;box-shadow:0 0 0 8px #fff}
.step .n{display:inline-grid;place-items:center;width:28px;height:28px;border-radius:50%;background:var(--g);color:#fff;font-size:11px;font-weight:700;margin:14px 0 8px}
.step b{display:block;color:var(--g2)}.step span{font-size:13.5px;color:var(--mut)}
.track{position:relative;border-radius:24px;overflow:hidden;color:#fff;padding:32px;background:linear-gradient(160deg,#0b3d3a,#0f5a52 60%,#1e7a63);min-height:300px}
.track:after{content:"";position:absolute;inset:auto -40px -40px auto;width:220px;height:220px;border-radius:50%;background:#f5b82e22;animation:pulse 4s ease-in-out infinite}
@keyframes pulse{50%{transform:scale(1.25);opacity:.5}}
.track>*{position:relative;z-index:1}
.track h3{font-size:20px;margin-bottom:8px}.track p{font-size:14px;opacity:.9;margin-bottom:18px}
.track input{width:100%;padding:14px 16px;border-radius:10px;border:0;font:inherit;margin-bottom:12px}
.track input:focus{outline:3px solid var(--gold)}
.track .btn{width:100%;justify-content:center;border-radius:10px}
#res{margin-top:14px;font-size:13px;min-height:20px;color:var(--gold2)}
.quote{margin-top:24px;border-radius:24px;padding:28px;background:linear-gradient(135deg,#f9efd6,#fff6e0)}
.quote q{font-size:20px;font-weight:700;display:block;margin-bottom:8px}
.mini{display:grid;grid-template-columns:1fr 1fr;gap:30px;margin-top:28px;padding-top:28px;border-top:1px solid #e3eae8}
.mini h4{margin-bottom:10px}
.mini li{list-style:none;font-size:14px;margin:7px 0;color:var(--mut)}
.mini li:before{content:"\2713";display:inline-grid;place-items:center;width:18px;height:18px;border-radius:50%;background:#14806a;color:#fff;font-size:11px;margin-right:10px}
.mini a{display:block;color:var(--g2);font-size:14px;margin:7px 0}
.mini a:hover{text-decoration:underline}
.dark-sec{background:var(--g);color:#fff}
.dark-sec .lead{color:#b9d1cc}.dark-sec .kick{color:var(--gold)}
.cards{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;margin-top:44px}
.pc{background:#ffffff10;border:1px solid #ffffff22;border-radius:20px;padding:30px;transition:transform .3s,background .3s}
.pc:hover{transform:translateY(-8px);background:#ffffff1c}
.pc .big{font-size:44px;font-weight:800;color:var(--gold);line-height:1}
.pc h3{margin:12px 0 8px}.pc p{font-size:14px;color:#c4dad6}
.pc ul{margin-top:14px;padding-left:18px;font-size:13.5px;color:#dbeae7}
.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:24px;text-align:center}
.stats b{display:block;font-size:46px;font-weight:800;color:var(--g2)}
.stats span{color:var(--mut);font-size:14px}
.tl{position:relative;max-width:760px;margin:44px auto 0;padding-left:36px}
.tl:before{content:"";position:absolute;left:11px;top:0;bottom:0;width:3px;background:linear-gradient(var(--gold) var(--p,0%),#d9e5e1 var(--p,0%))}
.ti{position:relative;background:#fff;border-radius:16px;padding:22px 26px;margin-bottom:22px;box-shadow:0 8px 24px #0b3d3a10}
.ti:before{content:"";position:absolute;left:-33px;top:26px;width:16px;height:16px;border-radius:50%;background:#fff;border:3px solid var(--gold)}
.ti b{color:var(--g2)}.ti p{font-size:14px;color:var(--mut)}
.faq{max-width:780px;margin:40px auto 0}
details{background:#fff;border-radius:14px;margin-bottom:12px;padding:0 24px;box-shadow:0 4px 16px #0b3d3a0d}
summary{cursor:pointer;padding:20px 0;font-weight:600;list-style:none;display:flex;justify-content:space-between}
summary:after{content:"+";font-size:22px;color:var(--g2);transition:transform .3s}
details[open] summary:after{transform:rotate(45deg)}
details p{padding-bottom:20px;color:var(--mut);font-size:14.5px}
.final{text-align:center;background:linear-gradient(120deg,var(--g),var(--g2));color:#fff;border-radius:28px;padding:70px 30px;position:relative;overflow:hidden}
.final p{margin:10px auto 28px;max-width:480px;opacity:.9}
footer{background:var(--g);color:#cfe3df;padding:40px 0;font-size:13px}
footer .wrap{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px}
footer a{margin-right:22px}
.rv{opacity:0;transform:translateY(40px);transition:opacity .8s,transform .8s}
.rv.vis{opacity:1;transform:none}
.d1{transition-delay:.12s}.d2{transition-delay:.24s}.d3{transition-delay:.36s}
.tt{position:fixed;right:22px;bottom:22px;width:46px;height:46px;border-radius:50%;background:var(--gold);color:var(--g);border:0;font-size:20px;cursor:pointer;opacity:0;transform:translateY(20px);transition:.3s;z-index:60}
.tt.show{opacity:1;transform:none}
@media(max-width:960px){
 nav{display:none}.script,.person{display:none}
 .trust .wrap,.stats{grid-template-columns:1fr 1fr}.t{border:0}
 .grid2,.cards,.mini{grid-template-columns:1fr}
 .steps{grid-template-columns:1fr 1fr}.steps:before{display:none}
 section{padding:60px 0}.panel{padding:26px}
}
@media(prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important}.rv{opacity:1;transform:none}}
a:focus-visible,button:focus-visible,summary:focus-visible{outline:3px solid var(--gold);outline-offset:3px}
.burger{display:none;width:44px;height:44px;border:0;border-radius:12px;background:#ffffff18;cursor:pointer;position:relative;flex:none}
.burger span,.burger:before,.burger:after{content:"";position:absolute;left:12px;right:12px;height:2px;background:#fff;border-radius:2px;transition:.3s}
.burger:before{top:14px}.burger span{top:21px}.burger:after{top:28px}
.burger.open:before{top:21px;transform:rotate(45deg)}.burger.open span{opacity:0}.burger.open:after{top:21px;transform:rotate(-45deg)}
@media(max-width:960px){
 .burger{display:block}
 header .wrap{gap:10px}
 header .btn{margin-left:auto}
 nav{display:flex;flex-direction:column;gap:0;position:absolute;left:0;right:0;top:100%;background:var(--g);padding:6px 24px 18px;opacity:0;visibility:hidden;transform:translateY(-12px);transition:.3s;box-shadow:0 14px 30px #0005}
 nav.open{opacity:1;visibility:visible;transform:none}
 nav a{padding:14px 0;border-bottom:1px solid #ffffff1a;font-size:16px}
 .logo small{display:none}
}
@media(max-width:560px){
 .logo b{font-size:13px}.logo i{width:38px;height:38px}
 header .btn{padding:8px 14px;font-size:13px}
 .hero{padding:120px 0 70px}
 .hero .cta .btn{width:100%;justify-content:center}
 .stats,.trust .wrap,.steps{grid-template-columns:1fr}
 .t{padding:0}
 footer .wrap{flex-direction:column;text-align:center}
}
.flow{background:linear-gradient(#fff,#eef5f2);overflow:hidden}
.fh{text-align:center;margin-bottom:64px}.fh .lead{margin:0 auto}
.rail{position:relative;max-width:1000px;margin:0 auto}
.rail:before,.rail:after{content:"";position:absolute;left:50%;top:0;width:4px;margin-left:-2px;border-radius:4px}
.rail:before{bottom:0;background:#d9e5e1}
.rail:after{height:var(--fp,0%);background:linear-gradient(var(--gold),var(--g2))}
.fs{position:relative;display:grid;grid-template-columns:1fr 1fr;gap:90px;align-items:center;margin-bottom:90px}
.fs:last-child{margin-bottom:0}
.fm{position:absolute;left:50%;top:50%;width:54px;height:54px;margin:-27px 0 0 -27px;border-radius:50%;background:#fff;border:4px solid #d9e5e1;display:grid;place-items:center;font-weight:800;color:var(--mut);z-index:2;transition:.5s}
.fs.vis .fm{background:var(--g);border-color:var(--gold);color:#fff;transform:scale(1.12);box-shadow:0 0 0 10px #f5b82e30}
.fc,.fv{opacity:0;transition:opacity .9s,transform .9s}
.fc{transform:translateX(-50px)}.fv{transform:translateX(50px)}
.fs:nth-child(even) .fc{order:2;transform:translateX(50px)}
.fs:nth-child(even) .fv{order:1;transform:translateX(-50px)}
.fs.vis .fc,.fs.vis .fv{opacity:1;transform:none}
.fs.vis .fv{transition-delay:.2s}
.fc h3{font-size:26px;margin:6px 0 10px;line-height:1.2}.fc p{color:var(--mut)}
.case{margin-top:18px;padding:14px 16px;border-left:4px solid var(--gold);background:#fff;border-radius:0 12px 12px 0;font-size:14px;box-shadow:0 6px 18px #0b3d3a10}
.case b{color:var(--g2)}
.scene{position:relative;aspect-ratio:4/3;border-radius:26px;overflow:hidden;box-shadow:0 22px 50px #0b3d3a30;display:grid;place-items:center}
.scene img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:5}
.s1{background:linear-gradient(135deg,#0f5a52,#2f8f6a)}
.s2{background:linear-gradient(135deg,#f5b82e,#f3d98a)}
.s3{background:linear-gradient(135deg,#12302d,#1e6b5c)}
.s4{background:linear-gradient(135deg,#e4f1ed,#bfe3d3)}
.ph{width:170px;height:290px;background:#fff;border-radius:28px;border:6px solid #12302d;padding:22px 14px;animation:bob 5s ease-in-out infinite}
.ph small{font-size:10px;font-weight:700;color:var(--g2)}
.fl{height:11px;border-radius:6px;background:#dbe7e3;margin:12px 0 4px;position:relative;overflow:hidden}
.fl:after{content:"";position:absolute;inset:0;width:0;background:var(--g2)}
.fs.vis .fl:after{animation:type 1.1s var(--d) steps(14) forwards}
.ph .chip{margin-top:14px;padding:8px;border-radius:10px;background:#e4f1ed;font-size:10px;font-weight:700;color:var(--g2);text-align:center;border:2px solid var(--gold);opacity:0}
.fs.vis .ph .chip{animation:pop .5s 2.3s forwards}
.ph .go{margin-top:12px;height:30px;border-radius:15px;background:var(--gold);color:var(--g);font-size:11px;font-weight:700;display:grid;place-items:center}
.docs{position:relative;width:300px;height:240px}
.dc{position:absolute;width:150px;height:100px;background:#fff;border-radius:12px;padding:12px;box-shadow:0 12px 26px #0003;font-size:10px;font-weight:700;color:var(--g2);opacity:0}
.dc i{display:block;height:6px;border-radius:3px;background:#dbe7e3;margin-top:8px}
.dc.a{left:0;top:10px;transform:translate(-120px,-60px) rotate(-14deg)}
.dc.b{right:0;top:60px;transform:translate(120px,-60px) rotate(12deg)}
.fs.vis .dc.a{animation:la .9s .3s forwards}.fs.vis .dc.b{animation:lb .9s .6s forwards}
.up{position:absolute;left:10px;right:10px;bottom:0;background:#fff;border-radius:14px;padding:12px 14px;font-size:11px;font-weight:700;box-shadow:0 12px 26px #0003}
.pb{height:8px;border-radius:4px;background:#dbe7e3;margin-top:8px;overflow:hidden}
.pb i{display:block;height:100%;width:0;background:var(--gold)}
.fs.vis .pb i{animation:fill 1.6s 1.2s forwards}
.up .ok{float:right;color:#14806a;opacity:0}.fs.vis .up .ok{animation:pop .4s 2.8s forwards}
.paper{position:relative;width:200px;height:250px;background:#fff;border-radius:10px;padding:20px;box-shadow:0 18px 40px #0006;overflow:visible}
.paper i{display:block;height:7px;border-radius:4px;background:#dbe7e3;margin-bottom:12px}
.paper .t{font-size:11px;font-weight:700;color:var(--g2);margin-bottom:12px}
.scan{position:absolute;left:0;right:0;top:10px;height:3px;background:var(--gold);box-shadow:0 0 14px var(--gold);opacity:0}
.fs.vis .scan{opacity:1;animation:scan 2s ease-in-out infinite alternate}
.stamp{position:absolute;right:-34px;bottom:30px;border:4px solid #14806a;color:#14806a;font-weight:800;font-size:20px;padding:4px 12px;border-radius:8px;background:#fffd;opacity:0;transform:rotate(-14deg) scale(3)}
.fs.vis .stamp{animation:thump .5s 2.2s forwards}
.who{position:absolute;left:14px;bottom:14px;background:#ffffff22;color:#fff;font-size:12px;padding:6px 12px;border-radius:20px}
.res{position:relative;width:250px;background:#fff;border-radius:22px;padding:26px 20px;text-align:center;box-shadow:0 18px 40px #0b3d3a30}
.res svg{width:84px;height:84px}
.ring,.chk{fill:none;stroke:#14806a;stroke-width:5;stroke-linecap:round;stroke-linejoin:round}
.ring{stroke-dasharray:214;stroke-dashoffset:214}.chk{stroke-dasharray:60;stroke-dashoffset:60}
.fs.vis .ring{animation:dash 1s .4s forwards}.fs.vis .chk{animation:dash .6s 1.2s forwards}
.res b{display:block;font-size:20px;margin:6px 0 2px}.res small{color:var(--mut)}
.sts{display:flex;gap:6px;justify-content:center;margin-top:14px;flex-wrap:wrap}
.sts span{font-size:10px;font-weight:700;padding:5px 9px;border-radius:20px;background:#eef2f1;color:#8a9b98}
.sts .on{background:#14806a;color:#fff;transform:scale(1.1)}
.cf{position:absolute;width:9px;height:9px;border-radius:2px;top:-10px;opacity:0}
.fs.vis .cf{animation:fall 2.2s var(--d) ease-in forwards}
@keyframes type{to{width:var(--w)}}
@keyframes pop{from{opacity:0;transform:scale(.7)}to{opacity:1;transform:none}}
@keyframes bob{50%{transform:translateY(-10px)}}
@keyframes la{to{opacity:1;transform:rotate(-6deg)}}
@keyframes lb{to{opacity:1;transform:rotate(5deg)}}
@keyframes fill{to{width:100%}}
@keyframes scan{to{top:96%}}
@keyframes thump{60%{transform:rotate(-14deg) scale(.9);opacity:1}to{opacity:1;transform:rotate(-14deg) scale(1)}}
@keyframes dash{to{stroke-dashoffset:0}}
@keyframes fall{10%{opacity:1}to{opacity:0;transform:translateY(330px) rotate(360deg)}}
@media(max-width:960px){
 .rail:before,.rail:after{left:27px}
 .fs{grid-template-columns:1fr;gap:20px;padding-left:72px;margin-bottom:60px}
 .fm{left:27px;top:0;margin-top:0}
 .fs:nth-child(even) .fc,.fs:nth-child(even) .fv{order:initial}
 .fc,.fv,.fs:nth-child(even) .fc,.fs:nth-child(even) .fv{transform:translateY(30px)}
 .fs.vis .fc,.fs.vis .fv,.fs.vis:nth-child(even) .fc,.fs.vis:nth-child(even) .fv{transform:none}
}
.dark-sec{position:relative;overflow:hidden}
.dark-sec .wrap{position:relative;z-index:2}
.pcoin{position:absolute;width:26px;height:26px;border-radius:50%;background:radial-gradient(circle at 30% 30%,#ffe08a,#c58a0b);box-shadow:inset 0 0 0 3px #ffffff40;opacity:.3;animation:cfloat var(--t,9s) ease-in-out infinite;z-index:1}
@keyframes cfloat{50%{transform:translateY(-34px) rotate(180deg)}}
.plans{perspective:1200px;align-items:stretch}
.plan{opacity:0;transform:translateY(70px) rotateX(22deg);transition:opacity .9s,transform .9s cubic-bezier(.2,.8,.2,1);transition-delay:calc(var(--i)*.18s)}
.plan.vis{opacity:1;transform:none}
.pin{position:relative;height:100%;background:#ffffff12;border:1px solid #ffffff26;border-radius:24px;overflow:hidden;display:flex;flex-direction:column;transform:rotateX(var(--rx,0deg)) rotateY(var(--ry,0deg));transition:transform .25s,box-shadow .3s,border-color .3s}
.plan:hover .pin{box-shadow:0 26px 60px #0009;border-color:var(--gold)}
.pin:after{content:"";position:absolute;top:0;left:-80%;width:50%;height:100%;background:linear-gradient(100deg,transparent,#ffffff30,transparent);transform:skewX(-20deg);transition:left .8s;pointer-events:none;z-index:5}
.plan:hover .pin:after{left:130%}
.pv{position:relative;height:190px;display:grid;place-items:center;overflow:hidden}
.pv1{background:linear-gradient(135deg,#1e7a63,#0f5a52)}
.pv2{background:linear-gradient(135deg,#f5b82e,#e59a14)}
.pv3{background:linear-gradient(135deg,#1b4f6b,#0b3d3a)}
.pv img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:3}
.pv svg{width:150px;height:150px;position:relative;z-index:2}
.pbody{padding:26px 26px 28px;display:flex;flex-direction:column;flex:1}
.pbody .lab{font-size:13px;font-weight:700;color:var(--gold)}
.pbody h3{margin:4px 0 8px;font-size:22px}
.pbody p{font-size:14px;color:#c4dad6}
.pbody ul{list-style:none;margin:18px 0 22px;padding:0;flex:1}
.pbody li{display:flex;gap:12px;align-items:center;font-size:14px;color:#e5f1ee;margin:11px 0;opacity:0;transform:translateX(-14px)}
.pbody li:before{content:"\2713";flex:none;width:22px;height:22px;border-radius:50%;background:var(--gold);color:var(--g);display:grid;place-items:center;font-size:12px;font-weight:800;transform:scale(0)}
.pbody li:nth-child(1){--k:0}.pbody li:nth-child(2){--k:1}.pbody li:nth-child(3){--k:2}
.plan.vis li{animation:pli .5s calc(.7s + var(--i)*.18s + var(--k)*.18s) forwards}
.plan.vis li:before{animation:ptick .4s calc(.9s + var(--i)*.18s + var(--k)*.18s) forwards}
.pbtn{align-self:flex-start;padding:11px 20px;font-size:14px}
@keyframes pli{to{opacity:1;transform:none}}
@keyframes ptick{60%{transform:scale(1.3)}to{transform:scale(1)}}
/* jar */
.lvl{transform-box:fill-box;transform-origin:bottom;transform:scaleY(.15)}
.plan.vis .lvl{animation:lvl 4.5s ease-in-out infinite}
.cn{opacity:0}
.plan.vis .cn{animation:cdrop 2.2s var(--d) ease-in infinite}
@keyframes lvl{0%{transform:scaleY(.15)}80%,100%{transform:scaleY(1)}}
@keyframes cdrop{0%{transform:translateY(0);opacity:0}15%{opacity:1}70%{transform:translateY(78px);opacity:1}100%{transform:translateY(90px);opacity:0}}
/* bars + cap */
.br{transform-box:fill-box;transform-origin:bottom;transform:scaleY(0)}
.plan.vis .br{animation:bgrow 4s var(--d) ease-out infinite}
.tas{transform-origin:125px 28px}
.plan.vis .tas{animation:sway 1.8s ease-in-out infinite alternate}
@keyframes bgrow{0%{transform:scaleY(0)}30%,88%{transform:scaleY(1)}100%{transform:scaleY(0)}}
@keyframes sway{from{transform:rotate(-12deg)}to{transform:rotate(12deg)}}
/* lock + ring */
.rg{stroke-dasharray:352;stroke-dashoffset:352;transform:rotate(-90deg);transform-origin:75px 75px}
.plan.vis .rg{animation:rdraw 4s ease-in-out infinite}
.sh{transform:translateY(-12px)}
.plan.vis .sh{animation:lclose 4s ease-in-out infinite}
@keyframes rdraw{0%{stroke-dashoffset:352}70%,100%{stroke-dashoffset:0}}
@keyframes lclose{0%,20%{transform:translateY(-12px)}40%,100%{transform:translateY(0)}}
@media(prefers-reduced-motion:reduce){
 .plan{opacity:1;transform:none}
 .pbody li{opacity:1;transform:none}
 .pbody li:before{transform:none}
 .lvl,.br{transform:none}.sh,.rg{transform:none;stroke-dashoffset:0}.rg{transform:rotate(-90deg)}
}
</style>
</head>
<body>
<div id="bar"></div>

<header id="hd"><div class="wrap">
  <a class="logo" href="landing.php"><i>CBE</i><span><b>Commercial Bank of Ethiopia</b><small>The Bank for a Brighter Future</small></span></a>
  <nav>
    <a class="on" href="#top">Home</a><a href="#products">Savings Accounts</a><a href="#requirements">Requirements</a><a href="#flow">How It Works</a><a href="#faq">Help &amp; Support</a>
  </nav>
  <a class="btn gold" href="login.php">Sign In</a>
  <button class="burger" id="bg" aria-label="Menu" aria-expanded="false"><span></span></button>
</div></header>

<main id="top">
<div class="hero">
  <svg class="sky" viewBox="0 0 1440 400" preserveAspectRatio="none" aria-hidden="true">
    <path d="M0 400V250L180 160 340 240 520 120 700 230 900 140 1100 230 1300 150 1440 220V400Z" fill="#b9cdbb" opacity=".55"/>
    <path d="M0 400V300L220 230 420 290 640 200 860 290 1080 220 1260 290 1440 250V400Z" fill="#8fb398" opacity=".7"/>
    <g fill="#2f6b5c" opacity=".85"><rect x="980" y="120" width="90" height="280"/><rect x="1080" y="200" width="60" height="200"/><rect x="900" y="250" width="70" height="150"/><rect x="1160" y="240" width="80" height="160"/><rect x="760" y="280" width="80" height="120"/></g>
    <path d="M0 400V340Q300 300 700 340T1440 330V400Z" fill="#3f8f6a"/>
  </svg>
  <div class="script">Your Future<br>Starts Here</div>
  <svg class="person" viewBox="0 0 300 470" aria-hidden="true">
    <path d="M40 470C40 330 90 290 150 290s110 40 110 180Z" fill="#d9c6a8"/>
    <path d="M110 290l40 120 40-120Z" fill="#fff"/>
    <rect x="132" y="240" width="36" height="60" rx="14" fill="#8a5a3b"/>
    <ellipse cx="150" cy="190" rx="52" ry="62" fill="#9b6a47"/>
    <path d="M92 190C80 90 150 60 205 110c30 30 15 90 5 120-6-40-20-70-60-80-35 10-50 30-58 40Z" fill="#2a1710"/>
    <path d="M128 210q22 22 44 0" stroke="#fff" stroke-width="5" fill="none" stroke-linecap="round"/>
  </svg>
  <div class="wrap">
    <span class="tag">SAVINGS ACCOUNT SERVICES</span>
    <h1>Open Your Savings Account with CBE</h1>
    <p>Secure your future today. Apply for a savings account, provide the required information and documents, and follow your application through the review process.</p>
    <div class="cta">
      <a class="btn dark" href="new.php">Start an Application &rarr;</a>
      <a class="btn out" href="#products">Learn About Our Savings Accounts</a>
    </div>
  </div>
</div>

<div class="trust"><div class="wrap">
  <div class="t rv"><div class="ic"><svg viewBox="0 0 24 24"><path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z"/><path d="M8.5 12l2.5 2.5L16 9.5"/></svg></div><div><b>Safe &amp; Trusted</b><span>Your information is secure with CBE.</span></div></div>
  <div class="t rv d1"><div class="ic"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></div><div><b>Simple &amp; Convenient</b><span>Complete your application online in a few steps.</span></div></div>
  <div class="t rv d2"><div class="ic"><svg viewBox="0 0 24 24"><path d="M7 3h8l4 4v14H7z"/><path d="M10 12h6M10 16h6"/></svg></div><div><b>Multiple Savings Products</b><span>Choose the account that fits your needs.</span></div></div>
  <div class="t rv d3"><div class="ic"><svg viewBox="0 0 24 24"><path d="M4 14v-2a8 8 0 0116 0v2"/><rect x="3" y="14" width="4" height="6" rx="1"/><rect x="17" y="14" width="4" height="6" rx="1"/></svg></div><div><b>Need Help?</b><span>Our support team is ready to assist you.</span></div></div>
</div></div>

<section class="flow" id="flow"><div class="wrap">
  <div class="fh"><div class="kick rv">THE APPLICATION PROCESS</div><h2 class="rv">It's easy. Just follow these steps.</h2><p class="lead rv">Follow Hana, a student in Nekemte, from her first form to her new account.</p></div>
  <div class="rail" id="rail">

    <div class="fs">
      <div class="fm">01</div>
      <div class="fc"><h3>Provide Your Details</h3><p>Enter your personal information and select the savings account you want to apply for.</p><div class="case"><b>Hana's case:</b> she types her name and phone number, then picks Personal Savings.</div></div>
      <div class="fv"><div class="scene s1"><img src="img/step1.jpg" alt="" onerror="this.remove()">
        <div class="ph"><small>New application</small>
          <div class="fl" style="--w:85%;--d:.4s"></div><div class="fl" style="--w:60%;--d:.9s"></div><div class="fl" style="--w:75%;--d:1.4s"></div>
          <div class="chip">Personal Savings</div><div class="go">Continue</div></div></div></div>
    </div>

    <div class="fs">
      <div class="fm">02</div>
      <div class="fc"><h3>Submit Your Documents</h3><p>Upload the required information and supporting documents.</p><div class="case"><b>Hana's case:</b> she adds her National ID and a residence letter from her kebele.</div></div>
      <div class="fv"><div class="scene s2"><img src="img/step2.jpg" alt="" onerror="this.remove()">
        <div class="docs"><div class="dc a">National ID<i></i><i style="width:70%"></i></div><div class="dc b">Residence letter<i></i><i style="width:60%"></i></div>
          <div class="up">Uploading 2 files <span class="ok">&#10003; Done</span><div class="pb"><i></i></div></div></div></div></div>
    </div>

    <div class="fs">
      <div class="fm">03</div>
      <div class="fc"><h3>Application Review</h3><p>The authorized bank reviewer checks your application and records a decision.</p><div class="case"><b>Hana's case:</b> the reviewer compares her ID with the form and records "approved".</div></div>
      <div class="fv"><div class="scene s3"><img src="img/step3.jpg" alt="" onerror="this.remove()">
        <div class="paper"><div class="t">Application form</div><i></i><i style="width:80%"></i><i></i><i style="width:55%"></i><i style="width:70%"></i><div class="scan"></div><div class="stamp">APPROVED</div></div>
        <div class="who">Reviewer at work</div></div></div>
    </div>

    <div class="fs">
      <div class="fm">04</div>
      <div class="fc"><h3>Receive the Outcome</h3><p>See whether your application has been approved, requires correction, or has been declined.</p><div class="case"><b>Hana's case:</b> she gets a reference number and sees her status: Approved.</div></div>
      <div class="fv"><div class="scene s4"><img src="img/step4.jpg" alt="" onerror="this.remove()">
        <span class="cf" style="left:20%;background:#f5b82e;--d:1.4s"></span><span class="cf" style="left:34%;background:#14806a;--d:1.6s"></span><span class="cf" style="left:50%;background:#f5b82e;--d:1.5s"></span><span class="cf" style="left:64%;background:#0f5a52;--d:1.8s"></span><span class="cf" style="left:78%;background:#f5b82e;--d:1.5s"></span>
        <div class="res"><svg viewBox="0 0 84 84"><circle class="ring" cx="42" cy="42" r="34"/><path class="chk" d="M27 43l11 11 20-23"/></svg><b>Approved</b><small>Reference: CBE-0142</small>
          <div class="sts"><span class="on">Approved</span><span>Needs correction</span><span>Declined</span></div></div></div></div>
    </div>

  </div>
</div></section>

<section id="how"><div class="wrap grid2">
  <div>
    <div class="panel rv d1" style="margin-top:24px;padding:28px">
      <div class="mini" style="margin:0;padding:0;border:0">
        <div><h4>Before You Begin</h4><ul><li>Valid identification document (e.g., National ID, Passport)</li><li>Proof of address (e.g., utility bill, residence letter)</li><li>Your contact information (phone number and email)</li></ul></div>
        <div><h4>Useful Information</h4><a href="#requirements">View document requirements &rarr;</a><a href="#products">Savings account types and benefits &rarr;</a><a href="#faq">Frequently asked questions &rarr;</a></div>
      </div>
    </div>
  </div>
  <aside>
    <div class="track rv d1">
      <h3>Track Your Application</h3>
      <p>Already applied? Check the status of your application using your reference number.</p>
      <input id="ref" placeholder="Enter your reference number" aria-label="Reference number">
      <button class="btn gold" id="chk">Check Status &rarr;</button>
      <div id="res" aria-live="polite"></div>
    </div>
    <div class="quote rv d2"><q>A stronger tomorrow starts with you.</q>Save a little, often.</div>
  </aside>
</div></section>

<section style="padding:20px 0 70px"><div class="wrap stats">
  <div class="rv"><b data-n="4">0</b><span>Steps to apply</span></div>
  <div class="rv d1"><b data-n="15">0</b><span>Days to complete documents</span></div>
  <div class="rv d2"><b data-n="3">0</b><span>Savings products</span></div>
  <div class="rv d3"><b data-n="24">0</b><span>Hour online access</span></div>
</div></section>

<section class="dark-sec" id="products"><span class="pcoin" style="left:5%;top:12%;--t:8s"></span><span class="pcoin" style="left:92%;top:22%;--t:10s;width:18px;height:18px"></span><span class="pcoin" style="left:12%;top:78%;--t:11s;width:20px;height:20px"></span><span class="pcoin" style="left:88%;top:80%;--t:9s"></span>
<div class="wrap">
  <div class="kick rv">SAVINGS PRODUCTS</div>
  <h2 class="rv">Choose the account that fits you.</h2>
  <p class="lead rv">Compare the demo products below. Check the tariff page for fees and rates.</p>
  <div class="cards plans">
    <article class="plan" style="--i:0"><div class="pin">
      <div class="pv pv1"><img src="img/plan1.jpg" alt="" onerror="this.remove()">
        <svg viewBox="0 0 150 150" aria-hidden="true">
          <rect class="lvl" x="42" y="70" width="66" height="62" rx="10" fill="#f5b82e"/>
          <rect x="38" y="48" width="74" height="88" rx="14" fill="#ffffff1f" stroke="#fff" stroke-width="4"/>
          <rect x="46" y="38" width="58" height="12" rx="5" fill="#fff"/>
          <circle class="cn" cx="60" cy="8" r="9" fill="#ffd76a" stroke="#b8860b" stroke-width="2" style="--d:0s"/>
          <circle class="cn" cx="75" cy="8" r="9" fill="#ffd76a" stroke="#b8860b" stroke-width="2" style="--d:.7s"/>
          <circle class="cn" cx="90" cy="8" r="9" fill="#ffd76a" stroke="#b8860b" stroke-width="2" style="--d:1.4s"/>
        </svg></div>
      <div class="pbody"><span class="lab">Basic</span><h3>Personal Savings</h3><p>An everyday account to start building savings.</p>
        <ul><li>Low opening deposit</li><li>Passbook included</li><li>Interest credited monthly</li></ul>
        <a class="btn gold pbtn" href="new.php">Apply for this account</a></div>
    </div></article>

    <article class="plan" style="--i:1"><div class="pin">
      <div class="pv pv2"><img src="img/plan2.jpg" alt="" onerror="this.remove()">
        <svg viewBox="0 0 150 150" aria-hidden="true">
          <rect class="br" x="30" y="115" width="24" height="25" rx="4" fill="#0b3d3a" style="--d:0s"/>
          <rect class="br" x="63" y="95" width="24" height="45" rx="4" fill="#0f5a52" style="--d:.25s"/>
          <rect class="br" x="96" y="70" width="24" height="70" rx="4" fill="#12302d" style="--d:.5s"/>
          <polygon points="75,8 125,28 75,48 25,28" fill="#12302d"/>
          <path d="M45 38v14c0 9 60 9 60 0V38" fill="#0f5a52"/>
          <g class="tas"><line x1="125" y1="28" x2="125" y2="54" stroke="#fff" stroke-width="3"/><circle cx="125" cy="57" r="5" fill="#fff"/></g>
        </svg></div>
      <div class="pbody"><span class="lab">Youth</span><h3>Student &amp; Youth Savings</h3><p>Made for students and first-time savers.</p>
        <ul><li>Simple requirements</li><li>Guardian option for minors</li><li>Goal-based saving</li></ul>
        <a class="btn gold pbtn" href="new.php">Apply for this account</a></div>
    </div></article>

    <article class="plan" style="--i:2"><div class="pin">
      <div class="pv pv3"><img src="img/plan3.jpg" alt="" onerror="this.remove()">
        <svg viewBox="0 0 150 150" aria-hidden="true">
          <circle cx="75" cy="75" r="56" fill="none" stroke="#ffffff30" stroke-width="8"/>
          <circle class="rg" cx="75" cy="75" r="56" fill="none" stroke="#f5b82e" stroke-width="8" stroke-linecap="round"/>
          <path class="sh" d="M60 74V62a15 15 0 0130 0v12" fill="none" stroke="#fff" stroke-width="7" stroke-linecap="round"/>
          <rect x="52" y="74" width="46" height="38" rx="7" fill="#fff"/>
          <circle cx="75" cy="91" r="4.5" fill="#0b3d3a"/><rect x="73" y="92" width="4" height="10" rx="2" fill="#0b3d3a"/>
        </svg></div>
      <div class="pbody"><span class="lab">Plus</span><h3>Fixed-Term Savings</h3><p>Lock in your money for a higher return.</p>
        <ul><li>Choose your term</li><li>Higher interest</li><li>Clear withdrawal rules</li></ul>
        <a class="btn gold pbtn" href="new.php">Apply for this account</a></div>
    </div></article>
  </div>

  <div style="text-align:center;margin-top:40px" class="rv"><a class="btn gold" href="tariff.php">See rates &amp; fees &rarr;</a></div>
</div></section>

<section id="requirements"><div class="wrap">
  <div style="text-align:center"><div class="kick rv">WHAT HAPPENS NEXT</div><h2 class="rv">From first form to active account</h2><p class="lead rv" style="margin:0 auto">Follow the line as you scroll. Each stage tells you what the bank does and what you do.</p></div>
  <div class="tl" id="tl">
    <div class="ti rv"><b>Application submitted</b><p>Your officer records your details, source of funds and any opening deposit.</p></div>
    <div class="ti rv"><b>Supervisor approval</b><p>A supervisor reviews the file. Approval starts a 15-day deadline for any missing documents.</p></div>
    <div class="ti rv"><b>Documents completed</b><p>Bring your ID and proof of address to the branch before the deadline.</p></div>
    <div class="ti rv"><b>Account active</b><p>Deposit, withdraw and earn month-end interest. Fees are listed on the tariff page.</p></div>
  </div>
</div></section>

<section id="faq" style="padding-top:20px"><div class="wrap">
  <div style="text-align:center"><div class="kick rv">HELP &amp; SUPPORT</div><h2 class="rv">Frequently asked questions</h2></div>
  <div class="faq">
    <details class="rv"><summary>What do I need to open an account?</summary><p>A valid ID (National ID or passport), proof of address, and your phone number and email.</p></details>
    <details class="rv"><summary>How long does review take?</summary><p>A supervisor reviews your application. Once approved, you have 15 days to complete any missing documents.</p></details>
    <details class="rv"><summary>Can I open an account with no deposit?</summary><p>In this demo, yes: you can start with a deposit of 0 and give a source of funds.</p></details>
    <details class="rv"><summary>Are these figures official?</summary><p>No. This is a student demo. Rates and fees come from published documents and may change. Confirm with the branch.</p></details>
  </div>
</div></section>

<section style="padding-top:0"><div class="wrap"><div class="final rv">
  <h2>Ready to start saving?</h2><p>It takes a few minutes. Start your application now.</p>
  <a class="btn gold" href="new.php">Start an Application &rarr;</a>
</div></div></section>
</main>

<footer><div class="wrap">
  <span>Commercial Bank of Ethiopia &middot; The Bank for a Brighter Future</span>
  <span><a href="#">Privacy Policy</a><a href="#">Terms &amp; Conditions</a><a href="#">Accessibility</a></span>
  <span>&copy; 2025 Student demo. Not a real bank system.</span>
</div></footer>

<button class="tt" id="tt" aria-label="Back to top">&uarr;</button>

<script>
const $=s=>document.querySelector(s),hd=$('#hd'),bar=$('#bar'),tt=$('#tt'),tl=$('#tl');
function onScroll(){
  const y=scrollY,h=document.documentElement.scrollHeight-innerHeight;
  bar.style.width=(y/h*100)+'%';
  hd.classList.toggle('small',y>40);
  tt.classList.toggle('show',y>600);
  const r=tl.getBoundingClientRect();
  tl.style.setProperty('--p',Math.max(0,Math.min(100,(innerHeight*.6-r.top)/r.height*100))+'%');
}
addEventListener('scroll',onScroll,{passive:true});onScroll();
tt.onclick=()=>scrollTo({top:0,behavior:'smooth'});
const io=new IntersectionObserver(es=>es.forEach(e=>{
  if(!e.isIntersecting)return;
  e.target.classList.add('vis');
  const n=e.target.querySelector&&e.target.querySelector('[data-n]');
  if(n)count(n);
  io.unobserve(e.target);
}),{threshold:.15});
document.querySelectorAll('.rv,#steps').forEach(el=>io.observe(el));
function count(el){
  const to=+el.dataset.n;let t0;
  const f=t=>{t0=t0||t;const p=Math.min((t-t0)/1200,1);el.textContent=Math.round(to*(1-Math.pow(1-p,3)));if(p<1)requestAnimationFrame(f)};
  requestAnimationFrame(f);
}
const links=[...document.querySelectorAll('nav a')];
const so=new IntersectionObserver(es=>es.forEach(e=>{
  if(e.isIntersecting){links.forEach(a=>a.classList.toggle('on',a.getAttribute('href')==='#'+e.target.id))}
}),{rootMargin:'-45% 0px -50% 0px'});
['top','products','requirements','how','flow','faq'].forEach(id=>{const el=document.getElementById(id);el&&so.observe(el)});
$('#chk').onclick=()=>{
  const v=$('#ref').value.trim(),r=$('#res');
  r.textContent=v?('Demo: no live lookup yet for "'+v+'". Log in to see applications.'):'Enter a reference number first.';
};
var bg=document.getElementById('bg'),nv=document.querySelector('nav');
function shut(){bg.classList.remove('open');nv.classList.remove('open');bg.setAttribute('aria-expanded','false')}
bg.onclick=function(){var o=nv.classList.toggle('open');bg.classList.toggle('open',o);bg.setAttribute('aria-expanded',o)};
nv.addEventListener('click',function(e){if(e.target.tagName==='A')shut()});
addEventListener('keydown',function(e){if(e.key==='Escape')shut()});
addEventListener('resize',function(){if(innerWidth>960)shut()});
var fsl=[].slice.call(document.querySelectorAll('.fs')),rail=document.getElementById('rail');
var fo=new IntersectionObserver(function(es){es.forEach(function(e){if(e.isIntersecting)e.target.classList.add('vis')})},{threshold:.35});
fsl.forEach(function(s){fo.observe(s)});
function rl(){var r=rail.getBoundingClientRect();rail.style.setProperty('--fp',Math.max(0,Math.min(100,(innerHeight*.55-r.top)/r.height*100))+'%')}
addEventListener('scroll',rl,{passive:true});rl();
(function(){
var plans=[].slice.call(document.querySelectorAll('.plan'));
var po=new IntersectionObserver(function(es){es.forEach(function(e){if(e.isIntersecting){e.target.classList.add('vis');po.unobserve(e.target)}})},{threshold:.25});
plans.forEach(function(p){po.observe(p)});
if(matchMedia('(hover:hover)').matches){
  plans.forEach(function(p){
    var pin=p.querySelector('.pin');
    p.addEventListener('mousemove',function(e){
      var r=p.getBoundingClientRect(),x=(e.clientX-r.left)/r.width-.5,y=(e.clientY-r.top)/r.height-.5;
      pin.style.setProperty('--ry',(x*10)+'deg');pin.style.setProperty('--rx',(-y*10)+'deg');
    });
    p.addEventListener('mouseleave',function(){pin.style.setProperty('--ry','0deg');pin.style.setProperty('--rx','0deg')});
  });
}
})();
</script>
</body>
</html>
