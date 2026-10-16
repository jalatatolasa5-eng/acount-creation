<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Open Your Savings Account | Commercial Bank of Ethiopia (Demo)</title>
<style>
:root{--g:#1a0533;--g2:#3b1470;--gold:#c8962e;--gold2:#f6ecd5;--ink:#1a0533;--mut:#6b5589;--bg:#f5f3fa;--card:#fff}
*{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:system-ui,-apple-system,"Segoe UI",Roboto,Arial,sans-serif;color:var(--ink);background:var(--bg);line-height:1.55}
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
[hidden]{display:none!important}
.more{margin-top:24px}
.pv{height:210px}
.pv .em{position:relative;z-index:2;font-size:70px;animation:bob 5s ease-in-out infinite}
.pv img{transition:transform .9s}
.plan:hover .pv img{transform:scale(1.08)}
#moreBtn{background:transparent;color:#fff;border-color:#ffffff66}
#moreBtn:hover{background:#ffffff1a}
.acc{padding:80px 0;background:#fff}
.acc:nth-of-type(even){background:var(--bg)}
.ag{display:grid;grid-template-columns:1.05fr 1fr;gap:70px;align-items:center}
.acc.rev .at{order:2}.acc.rev .av{order:1}
.ar{opacity:0;transform:translateY(28px);transition:opacity .8s var(--d,0s),transform .8s var(--d,0s)}
.acc.vis .ar{opacity:1;transform:none}
.at .kick{font-size:14px}
.at h2{font-size:clamp(28px,3.6vw,42px)}
.ap{list-style:none;margin:24px 0 28px}
.ap li{display:flex;gap:16px;align-items:flex-start;margin-bottom:16px}
.ap i{flex:none;width:36px;height:36px;border-radius:50%;background:var(--g2);color:#fff;display:grid;place-items:center;font-style:normal;font-weight:800}
.ap b{display:block}.ap span{font-size:14px;color:var(--mut)}
.av{position:relative;max-width:470px;width:100%;margin:0 auto;padding:22px 0}
.aph{position:relative;aspect-ratio:4/5;border-radius:24px;overflow:hidden;display:grid;place-items:center;box-shadow:0 24px 56px #0b3d3a30}
.aph span{font-size:96px}
.aph img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
.ac{position:absolute;background:#fff;border-radius:16px;padding:12px 16px;box-shadow:0 14px 32px #0b3d3a26;text-align:center;min-width:108px;z-index:2;opacity:0;transition:opacity .7s var(--cd)}
.acc.vis .ac{opacity:1}
.ac b{display:block;font-size:28px;font-weight:800;color:var(--g2);line-height:1.15}
.ac b.tx{font-size:15px;line-height:1.3;max-width:130px}
.ac span{font-size:12px;color:var(--mut)}
.a1{left:-28px;top:14%;--cd:.5s}
.a2{right:-24px;top:42%;--cd:.8s}
.a3{left:-20px;bottom:10%;--cd:1.1s}
@media(max-width:960px){
 .ag{grid-template-columns:1fr;gap:36px}
 .acc.rev .at,.acc.rev .av{order:initial}
 .a1,.a3{left:-6px}.a2{right:-6px}
 .acc{padding:56px 0}
}
@media(prefers-reduced-motion:reduce){.ar,.ac{opacity:1;transform:none}}
.br{background:linear-gradient(180deg,#eef5f2,#fff)}
.br.vis .ar,.br .ar.vis{opacity:1;transform:none}
.bg2{display:grid;grid-template-columns:1.1fr 1fr;gap:56px;align-items:center}
.bt h2{font-size:clamp(28px,3.6vw,42px)}
.bp{background:var(--g);color:#fff;border-radius:24px;padding:34px;box-shadow:0 22px 50px #0b3d3a30}
.bp h3{font-size:20px;margin-bottom:18px}
.bp ol{list-style:none;counter-reset:s;margin-bottom:26px}
.bp li{counter-increment:s;position:relative;padding-left:48px;margin-bottom:18px}
.bp li:before{content:counter(s);position:absolute;left:0;top:0;width:34px;height:34px;border-radius:50%;background:var(--gold);color:var(--g);display:grid;place-items:center;font-weight:800}
.bp b{display:block}.bp span{font-size:14px;color:#c4dad6}
.bj{margin-top:60px;text-align:center}
.bjh b{display:block;font-size:22px}.bjh span{color:var(--mut);font-size:14px}
.bjc{display:flex;flex-wrap:wrap;gap:12px;justify-content:center;margin:22px auto 0;max-width:820px}
.bjc a{padding:11px 20px;border-radius:30px;background:#fff;border:1px solid #cfe0db;font-weight:600;font-size:14px;color:var(--g2);transition:background .2s,color .2s,transform .2s}
.bjc a:hover{background:var(--g2);color:#fff;transform:translateY(-3px)}
.bjd{margin-top:26px;font-size:13px;font-weight:600;color:#a07100;animation:nudge 2s ease-in-out infinite}
@keyframes nudge{50%{transform:translateY(7px)}}
.acc{scroll-margin-top:70px}
@media(max-width:960px){.bg2{grid-template-columns:1fr;gap:32px}.bp{padding:26px}.bj{margin-top:44px}}
@media(prefers-reduced-motion:reduce){.bjd{animation:none}}
.loc{font-weight:600;color:#a07100;margin:-4px 0 14px}
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
    <div class="brname" style="display:inline-block;margin:12px 0 0;padding:7px 16px;border:1.5px solid var(--gold);border-radius:40px;background:#fffc;color:var(--g);font-weight:700;font-size:14px">&#128205; Commercial Bank of Ethiopia &middot; Horo Guduru Wayu Branch</div>
    <h1>Open Your Savings Account at CBE Horo Guduru Wayu Branch</h1>
    <p>Secure your future today. Visit the branch, and an officer opens your application and records your documents. A supervisor then reviews it.</p>
    <div class="cta">
      <a class="btn dark" href="login.php">Officer sign in &rarr;</a>
      <a class="btn out" href="#products">Learn About Our Savings Accounts</a>
    </div>
  </div>
</div>

<div class="trust"><div class="wrap">
  <div class="t rv"><div class="ic"><svg viewBox="0 0 24 24"><path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z"/><path d="M8.5 12l2.5 2.5L16 9.5"/></svg></div><div><b>Safe &amp; Trusted</b><span>Your information is secure with CBE.</span></div></div>
  <div class="t rv d1"><div class="ic"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></div><div><b>Simple &amp; Convenient</b><span>Your officer completes your application in a few steps.</span></div></div>
  <div class="t rv d2"><div class="ic"><svg viewBox="0 0 24 24"><path d="M7 3h8l4 4v14H7z"/><path d="M10 12h6M10 16h6"/></svg></div><div><b>Multiple Savings Products</b><span>Choose the account that fits your needs.</span></div></div>
  <div class="t rv d3"><div class="ic"><svg viewBox="0 0 24 24"><path d="M4 14v-2a8 8 0 0116 0v2"/><rect x="3" y="14" width="4" height="6" rx="1"/><rect x="17" y="14" width="4" height="6" rx="1"/></svg></div><div><b>Need Help?</b><span>Our support team is ready to assist you.</span></div></div>
</div></div>

<section class="flow" id="flow"><div class="wrap">
  <div class="fh"><div class="kick rv">THE APPLICATION PROCESS</div><h2 class="rv">It's easy. Just follow these steps.</h2><p class="lead rv">Follow Hana, a student in Nekemte, from her first form to her new account.</p></div>
  <div class="rail" id="rail">

    <div class="fs">
      <div class="fm">01</div>
      <div class="fc"><h3>Provide Your Details</h3><p>Give the officer your personal information and choose the savings account you want.</p><div class="case"><b>Hana's case:</b> she gives the officer her name and phone number, then picks Personal Savings.</div></div>
      <div class="fv"><div class="scene s1"><img src="img/step1.jpg" alt="" onerror="this.remove()">
        <div class="ph"><small>New application</small>
          <div class="fl" style="--w:85%;--d:.4s"></div><div class="fl" style="--w:60%;--d:.9s"></div><div class="fl" style="--w:75%;--d:1.4s"></div>
          <div class="chip">Personal Savings</div><div class="go">Continue</div></div></div></div>
    </div>

    <div class="fs">
      <div class="fm">02</div>
      <div class="fc"><h3>Submit Your Documents</h3><p>Hand the officer the required information and supporting documents.</p><div class="case"><b>Hana's case:</b> she hands over her National ID and a residence letter from her kebele.</div></div>
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
      <p>Already applied? Check the status of your application using your CIF or account number.</p>
      <input id="ref" placeholder="Enter your CIF or account number" aria-label="Reference number">
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

<section class="br" id="products"><div class="wrap">
  <div class="bg2">
    <div class="bt ar">
      <div class="kick">YOUR BRANCH</div>
      <h2>Open your account at CBE Horo Guduru Wayu Branch</h2>
    <p class="loc">Horo Guduru, Oromia</p>
      <p class="lead">A branch officer opens your application and records your details, and a supervisor approves the file. Bring your documents and your account is ready to use.</p>
      <p class="lead" style="margin-top:12px">Not sure which account fits you? Pick one below and read what it offers before you apply.</p>
    </div>
    <div class="bp ar" style="--d:.15s">
      <h3>At the branch</h3>
      <ol>
        <li><b>Bring your documents</b><span>A valid ID (National ID or passport), proof of address, and your phone number and email.</span></li>
        <li><b>Meet the officer</b><span>They enter your application and any opening deposit.</span></li>
        <li><b>Finish within 15 days</b><span>After approval, you have 15 days to hand in anything still missing.</span></li>
      </ol>
      <a class="btn gold" href="login.php">Officer sign in &rarr;</a>
    </div>
  </div>

  <div class="bj ar" style="--d:.3s">
    <div class="bjh"><b>Choose your account</b><span>Six accounts. Tap one to read about it.</span></div>
    <div class="bjc">
      <a href="#acc-ordinary">Ordinary</a><a href="#acc-youth">Youth</a><a href="#acc-women">Women</a><a href="#acc-education">Education</a><a href="#acc-wedding">Wedding</a><a href="#acc-holiday">Holiday</a>
    </div>
    <div class="bjd" aria-hidden="true">Scroll down to compare &darr;</div>
  </div>
</div></section>

<section class="acc" id="acc-ordinary"><div class="wrap ag">
  <div class="at ar">
    <div class="kick">Ordinary</div>
    <h2>Ordinary Savings</h2>
    <p class="lead">The standard CBE savings account. Your money earns interest while you keep it safe and add to it over time.</p>
    <ul class="ap">
      <li><i>1</i><div><b>Interest-bearing</b><span>Your balance earns interest.</span></div></li>
      <li><i>2</i><div><b>Simple to open</b><span>Bring your ID, proof of address and contact details.</span></div></li>
      <li><i>3</i><div><b>Regular saving</b><span>Deposit whenever you can.</span></div></li>
    </ul>
    <a class="btn dark" href="#products">What to bring to the branch &rarr;</a>
  </div>
  <div class="av ar" style="--d:.15s">
    <div class="aph" style="background:linear-gradient(135deg,#1e7a63,#0f5a52)"><span>💰</span><img src="img/ordinary-hero.jpg" alt="Ordinary Savings" loading="lazy" onerror="this.remove()"></div>
    <div class="ac a1"><b data-n="3">0</b><span>account perks</span></div>
    <div class="ac a2"><b data-n="4">0</b><span>steps to apply</span></div>
    <div class="ac a3"><b class="tx">Earns interest</b><span>key feature</span></div>
  </div>
</div></section>
<section class="acc rev" id="acc-youth"><div class="wrap ag">
  <div class="at ar">
    <div class="kick">Youth</div>
    <h2>Minor, Teen-Youth &amp; Youth Savings</h2>
    <p class="lead">These accounts help young people start saving early. Pick the one that matches your age group.</p>
    <ul class="ap">
      <li><i>1</i><div><b>Three age options</b><span>Minor, Teen-Youth and Youth accounts.</span></div></li>
      <li><i>2</i><div><b>Early saving habit</b><span>Built to start saving young.</span></div></li>
      <li><i>3</i><div><b>Minors</b><span>Ask your branch about the rules for opening for a child.</span></div></li>
    </ul>
    <a class="btn dark" href="#products">What to bring to the branch &rarr;</a>
  </div>
  <div class="av ar" style="--d:.15s">
    <div class="aph" style="background:linear-gradient(135deg,#f5b82e,#e59a14)"><span>🎓</span><img src="img/youth-hero.jpg" alt="Minor, Teen-Youth &amp; Youth Savings" loading="lazy" onerror="this.remove()"></div>
    <div class="ac a1"><b data-n="3">0</b><span>account perks</span></div>
    <div class="ac a2"><b data-n="4">0</b><span>steps to apply</span></div>
    <div class="ac a3"><b class="tx">Made for young savers</b><span>key feature</span></div>
  </div>
</div></section>
<section class="acc" id="acc-women"><div class="wrap ag">
  <div class="at ar">
    <div class="kick">Women</div>
    <h2>Women Saving Account</h2>
    <p class="lead">A savings account made for women. It pays higher interest than an ordinary account and adds everyday rewards.</p>
    <ul class="ap">
      <li><i>1</i><div><b>Higher interest</b><span>Your balance grows faster.</span></div></li>
      <li><i>2</i><div><b>Cashback</b><span>Get money back on your spending.</span></div></li>
      <li><i>3</i><div><b>Partner discounts</b><span>Save at partner shops and services.</span></div></li>
    </ul>
    <a class="btn dark" href="#products">What to bring to the branch &rarr;</a>
  </div>
  <div class="av ar" style="--d:.15s">
    <div class="aph" style="background:linear-gradient(135deg,#b5527a,#6e2a4b)"><span>👩🏾</span><img src="img/women-hero.jpg" alt="Women Saving Account" loading="lazy" onerror="this.remove()"></div>
    <div class="ac a1"><b data-n="3">0</b><span>account perks</span></div>
    <div class="ac a2"><b data-n="4">0</b><span>steps to apply</span></div>
    <div class="ac a3"><b class="tx">Higher interest</b><span>key feature</span></div>
  </div>
</div></section>
<section class="acc rev" id="acc-education"><div class="wrap ag">
  <div class="at ar">
    <div class="kick">Education</div>
    <h2>Education Saving Account</h2>
    <p class="lead">Save for school and university costs, and earn more than on an ordinary account.</p>
    <ul class="ap">
      <li><i>1</i><div><b>Above-ordinary interest</b><span>A better rate than ordinary savings.</span></div></li>
      <li><i>2</i><div><b>Incentives</b><span>Extra rewards for saving.</span></div></li>
      <li><i>3</i><div><b>Discounts</b><span>Savings on partner offers.</span></div></li>
    </ul>
    <a class="btn dark" href="#products">What to bring to the branch &rarr;</a>
  </div>
  <div class="av ar" style="--d:.15s">
    <div class="aph" style="background:linear-gradient(135deg,#1b4f6b,#0b3d3a)"><span>📚</span><img src="img/education-hero.jpg" alt="Education Saving Account" loading="lazy" onerror="this.remove()"></div>
    <div class="ac a1"><b data-n="3">0</b><span>account perks</span></div>
    <div class="ac a2"><b data-n="4">0</b><span>steps to apply</span></div>
    <div class="ac a3"><b class="tx">For school costs</b><span>key feature</span></div>
  </div>
</div></section>
<section class="acc" id="acc-wedding"><div class="wrap ag">
  <div class="at ar">
    <div class="kick">Wedding</div>
    <h2>Wedding Saving Account</h2>
    <p class="lead">Save toward your wedding with a better rate than ordinary savings.</p>
    <ul class="ap">
      <li><i>1</i><div><b>Above-ordinary interest</b><span>A better rate than ordinary savings.</span></div></li>
      <li><i>2</i><div><b>Incentives</b><span>Extra rewards for saving.</span></div></li>
      <li><i>3</i><div><b>Discounts</b><span>Savings on partner offers.</span></div></li>
    </ul>
    <a class="btn dark" href="#products">What to bring to the branch &rarr;</a>
  </div>
  <div class="av ar" style="--d:.15s">
    <div class="aph" style="background:linear-gradient(135deg,#c0568a,#7a2a55)"><span>💍</span><img src="img/wedding-hero.jpg" alt="Wedding Saving Account" loading="lazy" onerror="this.remove()"></div>
    <div class="ac a1"><b data-n="3">0</b><span>account perks</span></div>
    <div class="ac a2"><b data-n="4">0</b><span>steps to apply</span></div>
    <div class="ac a3"><b class="tx">For your big day</b><span>key feature</span></div>
  </div>
</div></section>
<section class="acc rev" id="acc-holiday"><div class="wrap ag">
  <div class="at ar">
    <div class="kick">Holiday</div>
    <h2>Holiday Saving Account</h2>
    <p class="lead">Set money aside for holidays and celebrations and earn above-ordinary interest.</p>
    <ul class="ap">
      <li><i>1</i><div><b>Above-ordinary interest</b><span>A better rate than ordinary savings.</span></div></li>
      <li><i>2</i><div><b>Incentives</b><span>Extra rewards for saving.</span></div></li>
      <li><i>3</i><div><b>Discounts</b><span>Savings on partner offers.</span></div></li>
    </ul>
    <a class="btn dark" href="#products">What to bring to the branch &rarr;</a>
  </div>
  <div class="av ar" style="--d:.15s">
    <div class="aph" style="background:linear-gradient(135deg,#1f9db8,#0b5d78)"><span>🏖️</span><img src="img/holiday-hero.jpg" alt="Holiday Saving Account" loading="lazy" onerror="this.remove()"></div>
    <div class="ac a1"><b data-n="3">0</b><span>account perks</span></div>
    <div class="ac a2"><b data-n="4">0</b><span>steps to apply</span></div>
    <div class="ac a3"><b class="tx">Plan ahead</b><span>key feature</span></div>
  </div>
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
  <a class="btn gold" href="login.php">Officer sign in &rarr;</a>
</div></div></section>
</main>

<footer><div class="wrap">
  <span>Commercial Bank of Ethiopia &middot; The Bank for a Brighter Future</span>
  <span><a href="#">Privacy Policy</a><a href="#">Terms &amp; Conditions</a><a href="#">Accessibility</a></span>
  <span>&copy; 2025 Student demo. Not a real bank system.</span>
</div></footer>

<style>
.cbhelp{background:var(--g);color:#fff;padding:56px 0}
.cbhelp h2{font-size:clamp(22px,3.4vw,30px);margin-bottom:6px}
.cbhelp .sub{color:#d9cdee;margin-bottom:24px;max-width:620px}
.cbhelp-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:18px}
.cbhelp-box{background:#ffffff14;border:1px solid #ffffff2e;border-radius:16px;padding:18px}
.cbhelp-box small{display:block;color:var(--gold);font-weight:700;letter-spacing:.04em;text-transform:uppercase;font-size:12px;margin-bottom:6px}
.cbhelp-box a,.cbhelp-box span{color:#fff;font-size:18px;font-weight:700;word-break:break-word}
.cbhelp-note{margin-top:22px;font-size:13px;color:#d9cdee}
.cbhelp-note a{color:var(--gold);font-weight:700}
</style>
<section class="cbhelp" id="cbhelp"><div class="wrap">
  <h2>Need help? Talk to CBE</h2>
  <p class="sub">Call or write to the CBE contact center, or ask at the Horo Guduru Wayu branch.</p>
  <div class="cbhelp-grid">
    <div class="cbhelp-box"><small>Contact center (Ethiopia)</small><a href="tel:951">951</a></div>
    <div class="cbhelp-box"><small>From abroad</small><a href="tel:+251113000000">+251-113-000000</a></div>
    <div class="cbhelp-box"><small>Email</small><a href="mailto:cbecontactcenter@cbe.com.et">cbecontactcenter@cbe.com.et</a></div>
    <div class="cbhelp-box"><small>Your branch</small><span>Horo Guduru Wayu Branch</span></div>
  </div>
  <p class="cbhelp-note">Lost your phone or forgot your mobile banking PIN? Call 951 or visit any CBE branch. This is a demo project, not the official website. See <a href="https://combanketh.et" target="_blank" rel="noopener">combanketh.et</a> for current details.</p>
</div></section>
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
$('#chk').onclick=function(){
  var v=$('#ref').value.trim(),r=$('#res');
  if(!v){r.textContent='Enter your CIF or account number first.';return}
  r.textContent='Checking...';
  fetch('status.php?ref='+encodeURIComponent(v))
    .then(function(x){return x.json()})
    .then(function(d){r.textContent=d.ok?(d.type+': '+d.msg):d.msg})
    .catch(function(){r.textContent='Could not reach the server.'});
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
(function(){
var b=document.getElementById('moreBtn'),m=document.getElementById('morePlans');
if(!b)return;
b.onclick=function(){
  var open=m.hasAttribute('hidden');
  if(open){m.removeAttribute('hidden');b.textContent='Show fewer account types'}
  else{m.setAttribute('hidden','');b.textContent='Show more account types'}
  b.setAttribute('aria-expanded',open);
};
})();
(function(){
var s=[].slice.call(document.querySelectorAll('.acc'));
var o=new IntersectionObserver(function(es){es.forEach(function(e){
  if(!e.isIntersecting)return;
  e.target.classList.add('vis');
  setTimeout(function(){[].forEach.call(e.target.querySelectorAll('[data-n]'),count)},600);
  o.unobserve(e.target);
})},{threshold:.25});
s.forEach(function(x){o.observe(x)});
})();
(function(){
var b=document.querySelector('.br');if(!b)return;
var o=new IntersectionObserver(function(es){es.forEach(function(e){if(e.isIntersecting){b.classList.add('vis');o.disconnect()}})},{threshold:.2});
o.observe(b);
})();
</script>
</body>
</html>
