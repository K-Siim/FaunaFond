<template>
  <div class="bg-[#e8f0e5] text-[#0e2c20] font-sans antialiased overflow-x-hidden" style="font-family: 'DM Sans', system-ui, sans-serif;">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet" />

    <!-- NAV -->
    <nav class="sticky top-0 z-[100] bg-[#e8f0e5]/90 backdrop-blur-md border-b border-[#275342]/10">
      <div class="max-w-[1160px] mx-auto px-8 flex items-center gap-8 py-[18px]">
        <a href="#" class="flex items-center gap-2.5 text-xl font-bold text-[#275342] no-underline shrink-0">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/>
            <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>
          </svg>
          FaunaFond
        </a>
        <div class="hidden md:flex gap-7 ml-auto">
          <a href="#probleem" class="text-[#0e2c20] no-underline text-[15px] font-medium opacity-80 hover:opacity-100 transition-opacity">Probleem</a>
          <a href="#funktsioonid" class="text-[#0e2c20] no-underline text-[15px] font-medium opacity-80 hover:opacity-100 transition-opacity">Funktsioonid</a>
          <a href="#kuidas" class="text-[#0e2c20] no-underline text-[15px] font-medium opacity-80 hover:opacity-100 transition-opacity">Kuidas töötab</a>
        </div>
        <Link :href="route('login')" class="hidden md:inline-flex items-center gap-2 bg-[#275342] text-[#fffdf5] font-semibold rounded-full px-[22px] py-2.5 text-sm no-underline shadow-[0_4px_16px_rgba(39,83,66,0.25)] hover:shadow-[0_6px_24px_rgba(39,83,66,0.35)] hover:-translate-y-px transition-all whitespace-nowrap">
          Logi sisse
        </Link>
        <button class="flex md:hidden flex-col gap-[5px] bg-transparent border-none cursor-pointer p-1 ml-auto" @click="mobileOpen = !mobileOpen" :aria-expanded="mobileOpen">
          <span class="block w-[22px] h-0.5 bg-[#0e2c20] rounded-sm"></span>
          <span class="block w-[22px] h-0.5 bg-[#0e2c20] rounded-sm"></span>
          <span class="block w-[22px] h-0.5 bg-[#0e2c20] rounded-sm"></span>
        </button>
      </div>
      <div v-if="mobileOpen" class="flex flex-col gap-4 px-8 pt-5 pb-6 border-t border-[#275342]/10">
        <a href="#probleem" @click="mobileOpen = false" class="text-[#0e2c20] no-underline text-base font-medium">Probleem</a>
        <a href="#funktsioonid" @click="mobileOpen = false" class="text-[#0e2c20] no-underline text-base font-medium">Funktsioonid</a>
        <a href="#kuidas" @click="mobileOpen = false" class="text-[#0e2c20] no-underline text-base font-medium">Kuidas töötab</a>
        <Link :href="route('login')" @click="mobileOpen = false" class="inline-flex items-center justify-center bg-[#275342] text-[#fffdf5] font-semibold rounded-full px-9 py-[18px] text-base no-underline">Logi sisse</Link>
      </div>
    </nav>

    <main>
      <!-- HERO -->
      <section class="relative py-24 pb-[120px] overflow-hidden">
        <div class="absolute top-[-100px] right-[-150px] w-[700px] h-[700px] bg-[radial-gradient(circle,rgba(39,83,66,0.12)_0%,transparent_70%)] rounded-full pointer-events-none"></div>
        <div class="max-w-[1160px] mx-auto px-8 grid grid-cols-1 md:grid-cols-[1.1fr_0.9fr] gap-20 items-center">
          <div>
            <div class="inline-flex items-center gap-2 text-[13px] font-semibold tracking-[0.04em] uppercase text-[#275342] opacity-80 mb-5">
              <span class="w-2 h-2 bg-[#275342] rounded-full inline-block"></span>
              Digitaalne tervisepass lemmikloomadele
            </div>
            <h1 class="text-[clamp(40px,5vw,60px)] leading-[1.08] tracking-[-0.5px] mb-6" style="font-family: 'DM Serif Display', Georgia, serif;">
              Lemmiklooma tervis,<br>
              <em class="italic text-[#275342]">alati sinuga</em> kaasas.
            </h1>
            <p class="text-lg leading-[1.65] opacity-75 mb-10 max-w-[480px]">
              FaunaFond on mugav ja turvaline digitaalne tervisepass sinu lemmikloomale.
              Unusta paberimajandus ja hoia vaktsiinid, ravimid ning arstivisiidid alati
              käepärast ühes rakenduses.
            </p>
            <div class="flex gap-3.5 flex-wrap">
              <Link :href="route('register')" class="inline-flex items-center gap-2 bg-[#275342] text-[#fffdf5] font-semibold rounded-full px-9 py-[18px] text-base no-underline shadow-[0_4px_16px_rgba(39,83,66,0.25)] hover:shadow-[0_6px_24px_rgba(39,83,66,0.35)] hover:-translate-y-px transition-all">
                Alusta tasuta
              </Link>
              <a href="#kuidas" class="inline-flex items-center gap-2 bg-transparent text-[#0e2c20] font-semibold rounded-full px-9 py-[18px] text-base no-underline border border-[#275342]/20 hover:-translate-y-px transition-all">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8"/></svg>
                Kuidas see töötab?
              </a>
            </div>
          </div>

          <!-- PHONE MOCKUP -->
          <div class="hidden md:flex justify-center" aria-hidden="true">
            <div class="w-[300px] bg-[#e8f0e5] border-[10px] border-[#0e2c20] rounded-[44px] overflow-hidden shadow-[0_40px_80px_rgba(14,44,32,0.2),0_0_0_1px_rgba(0,0,0,0.05)] relative shrink-0">
              <div class="w-[100px] h-6 bg-[#0e2c20] rounded-b-[18px] mx-auto mb-1"></div>
              <div class="px-4 pb-5 overflow-hidden">
                <div class="flex justify-between items-center mb-3.5 text-[#0e2c20]">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                </div>
                <h3 class="text-lg font-bold mb-[18px]">Tere, Kasutaja 👋</h3>

                <div class="mb-[18px]">
                  <div class="flex justify-between items-center text-xs font-bold mb-2.5 text-[#0e2c20]">
                    <span>Lemmikute nimekiri</span>
                    <span class="text-[11px] opacity-60 font-medium cursor-pointer">Vaata kõiki</span>
                  </div>
                  <div class="bg-[#fffdf5] rounded-xl p-2.5 px-3 flex gap-3 items-center mb-2">
                    <img src="https://storage.googleapis.com/banani-generated-images/generated-images/0820d631-bbf7-4358-8282-bfb610055f9c.jpg" alt="Milli-Malli" class="w-12 h-12 rounded-[10px] object-cover shrink-0" />
                    <div class="flex flex-col gap-0.5">
                      <strong class="text-[13px]">Milli-Malli</strong>
                      <span class="text-[11px] opacity-70">Kass · 17 aastat</span>
                    </div>
                  </div>
                  <div class="bg-[#fffdf5] rounded-xl p-2.5 px-3 flex gap-3 items-center mb-2">
                    <img src="https://storage.googleapis.com/banani-generated-images/generated-images/0692e44a-b94a-4066-9b45-00ffac9c27a2.jpg" alt="Lota" class="w-12 h-12 rounded-[10px] object-cover shrink-0" />
                    <div class="flex flex-col gap-0.5">
                      <strong class="text-[13px]">Lota</strong>
                      <span class="text-[11px] opacity-70">Koer · 1 aasta · 46 kg</span>
                    </div>
                  </div>
                </div>

                <div class="mb-[18px]">
                  <div class="flex justify-between items-center text-xs font-bold mb-2.5 text-[#0e2c20]">
                    <span>Meeldetuletused</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                  </div>
                  <div class="flex gap-2.5 items-start p-2.5 px-3 rounded-xl text-[11px] leading-[1.4] font-medium mb-2 bg-[#ffcbc7]">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 mt-px"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>"Milli-Malli" marutaudi vaktsiin aegub "4. jaanuaril 2026"</span>
                  </div>
                  <div class="flex gap-2.5 items-start p-2.5 px-3 rounded-xl text-[11px] leading-[1.4] font-medium mb-2 bg-[#fff6ca]">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 mt-px"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    <span>"Lota" vaktsineerimise kuupäev on "15. oktoober 2025"</span>
                  </div>
                  <div class="flex gap-2.5 items-start p-2.5 px-3 rounded-xl text-[11px] leading-[1.4] font-medium mb-2 bg-[#d5eeff]">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 mt-px"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span>"Lota" arstivisiidi kuupäev on "15. oktoober 2025"</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- PROBLEM -->
      <section class="bg-[#fffdf5] py-28" id="probleem">
        <div class="max-w-[1160px] mx-auto px-8">
          <div class="text-center max-w-[660px] mx-auto mb-16">
            <p class="text-xs font-bold tracking-[0.1em] uppercase text-[#275342] opacity-70 mb-3">Probleemid</p>
            <h2 class="text-[clamp(30px,4vw,42px)] mb-4 leading-[1.1]" style="font-family: 'DM Serif Display', Georgia, serif;">Miks vajad ühtset tervisepassi?</h2>
            <p class="text-[17px] opacity-75 leading-[1.65]">Lemmiklooma tervise eest hoolitsemine nõuab omanikult täpsust. Killustatud info võib viia vigade ja asjatu stressini.</p>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-7">
            <div v-for="p in problems" :key="p.title" class="bg-[#e8f0e5] p-10 rounded-3xl hover:-translate-y-1 transition-transform">
              <div class="w-12 h-12 bg-[#275342]/10 rounded-xl flex items-center justify-center mb-5 text-[#275342]" v-html="p.icon"></div>
              <h3 class="text-lg font-bold mb-2.5">{{ p.title }}</h3>
              <p class="text-sm opacity-75 leading-[1.6]">{{ p.desc }}</p>
            </div>
          </div>
        </div>
      </section>

      <!-- FEATURES -->
      <section class="py-12" id="funktsioonid">
        <div class="max-w-[1160px] mx-auto px-8">

          <!-- Feature 1 -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-20 items-center py-20">
            <div>
              <div class="inline-flex items-center gap-2 bg-[#275342]/10 text-[#275342] px-4 py-1.5 rounded-full text-[13px] font-bold mb-6">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Mitme lemmiku haldus
              </div>
              <h2 class="text-[clamp(28px,3.5vw,40px)] leading-[1.1] mb-5" style="font-family: 'DM Serif Display', Georgia, serif;">Kõik sinu lemmikud ühes turvalises kohas</h2>
              <p class="text-[17px] opacity-75 leading-[1.65] mb-7">Lisa rakendusse piiramatult lemmikloomi. Igal loomal on oma isiklik profiil, mis koondab olulised andmed kiibi numbrist kuni kaalu ja tõuni.</p>
              <ul class="list-none p-0 m-0 flex flex-col gap-3.5">
                <li v-for="item in feature1List" :key="item" class="flex items-center gap-3 text-[15px] font-medium">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="text-[#275342] shrink-0"><polyline points="20 6 9 17 4 12"/></svg>
                  {{ item }}
                </li>
              </ul>
            </div>
            <div>
              <img
                src="https://storage.googleapis.com/banani-generated-images/generated-images/41d03c18-68f1-4a97-8817-86175e272465.jpg"
                alt="Lemmikud koos"
                class="w-full rounded-[36px] object-cover shadow-[0_24px_48px_rgba(14,44,32,0.12)] aspect-[4/3]"
              />
            </div>
          </div>

          <!-- Feature 2 -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-20 items-center py-20 border-t border-[#275342]/10" id="kuidas">
            <div class="flex justify-center order-first md:order-none">
              <div class="w-[280px] bg-[#e8f0e5] border-[10px] border-[#0e2c20] rounded-[44px] overflow-hidden shadow-[0_40px_80px_rgba(14,44,32,0.2)] relative shrink-0">
                <div class="w-[80px] h-5 bg-[#0e2c20] rounded-b-[18px] mx-auto mb-1"></div>
                <div class="px-4 pb-5 overflow-hidden">
                  <div class="flex justify-between items-center mb-3.5 text-[#0e2c20]">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                  </div>
                  <div class="flex items-center gap-3 mb-4 mt-2">
                    <img src="https://storage.googleapis.com/banani-generated-images/generated-images/0820d631-bbf7-4358-8282-bfb610055f9c.jpg" alt="Milli-Malli" class="w-14 h-14 rounded-xl object-cover shrink-0" />
                    <h3 class="flex-1 text-[17px] font-bold m-0">Milli-Malli</h3>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-[#275342] shrink-0"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                  </div>
                  <div class="bg-[#fffdf5] rounded-xl p-3 px-3.5 flex flex-col gap-2.5 text-[11px]">
                    <div class="flex justify-between"><span class="opacity-70">Kiip</span><strong>****</strong></div>
                    <div class="flex justify-between"><span class="opacity-70">Liik</span><strong>Kass</strong></div>
                    <div class="flex justify-between"><span class="opacity-70">Tõug</span><strong>Segavereline</strong></div>
                    <div class="flex justify-between"><span class="opacity-70">Sugu</span><strong>Emane</strong></div>
                    <div class="flex justify-between"><span class="opacity-70">Vanus</span><strong>06.10.2008 (17)</strong></div>
                  </div>
                  <div class="mt-4">
                    <div class="flex justify-between items-center text-xs font-bold mb-2 text-[#0e2c20]">
                      <span>Meditsiiniline info</span>
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                    </div>
                    <div class="bg-[#fffdf5] rounded-xl p-3 px-3.5">
                      <div class="text-[11px] font-bold mb-2">Vaktsiinid</div>
                      <div class="flex justify-between text-[10px] mb-1.5 opacity-85"><span>💉 Eurican CHPPi2-L</span><span>16.02.2014</span></div>
                      <div class="flex justify-between text-[10px] mb-1.5 opacity-85"><span>💉 Rabisin</span><span>23.07.2015</span></div>
                      <div class="text-[11px] font-bold mt-3 mb-2">Ravimid</div>
                      <div class="flex justify-between text-[10px] opacity-85"><span>💊 Thiamacare (10mg/ml)</span><span>5ml, 2x</span></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div>
              <div class="inline-flex items-center gap-2 bg-[#275342]/10 text-[#275342] px-4 py-1.5 rounded-full text-[13px] font-bold mb-6">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                Terviselugu ja profiil
              </div>
              <h2 class="text-[clamp(28px,3.5vw,40px)] leading-[1.1] mb-5" style="font-family: 'DM Serif Display', Georgia, serif;">Täielik ülevaade meditsiinilisest ajaloost</h2>
              <p class="text-[17px] opacity-75 leading-[1.65] mb-7">Jälgi vaktsineerimisi, ravimikuure ja arstivisiitide logi. Süsteem hoiatab sind lähenevatest tähtaegadest nutikate värvikoodidega meeldetuletuste abil.</p>
              <ul class="list-none p-0 m-0 flex flex-col gap-3.5">
                <li v-for="item in feature2List" :key="item" class="flex items-center gap-3 text-[15px] font-medium">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="text-[#275342] shrink-0"><polyline points="20 6 9 17 4 12"/></svg>
                  {{ item }}
                </li>
              </ul>
            </div>
          </div>

        </div>
      </section>

      <!-- CTA -->
      <section class="bg-[#275342] text-[#fffdf5] py-[120px] text-center relative overflow-hidden">
        <div class="absolute inset-0 pointer-events-none" style="background-image: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.06) 0%, transparent 50%), radial-gradient(circle at 80% 20%, rgba(255,255,255,0.05) 0%, transparent 40%);"></div>
        <div class="relative max-w-[700px] mx-auto px-8">
          <div class="opacity-[0.12] mb-6">
            <svg width="80" height="80" viewBox="0 0 24 24" fill="currentColor" class="mx-auto"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
          </div>
          <h2 class="text-[clamp(32px,5vw,50px)] leading-[1.1] mb-5" style="font-family: 'DM Serif Display', Georgia, serif;">Loo alus oma lemmiku paremale tervisele</h2>
          <p class="text-lg opacity-85 leading-[1.65] mb-10">Liitu FaunaFondiga juba täna. Progressiivne veebirakendus töötab sujuvalt igas seadmes, tagades ligipääsu andmetele igal ajal ja igas kohas.</p>
          <Link :href="route('register')" class="inline-flex items-center gap-2 bg-[#fffdf5] text-[#0e2c20] font-semibold rounded-full px-9 py-[18px] text-base no-underline shadow-[0_4px_20px_rgba(0,0,0,0.1)] hover:-translate-y-px transition-all">
            Loo tasuta konto &rarr;
          </Link>
        </div>
      </section>
    </main>

    <!-- FOOTER -->
    <footer class="bg-[#fffdf5] pt-14 pb-7">
      <div class="max-w-[1160px] mx-auto px-8 flex justify-between items-center pb-10 border-b border-[#275342]/10 flex-wrap gap-5">
        <a href="#" class="flex items-center gap-2.5 text-xl font-bold text-[#275342] no-underline">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/>
            <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>
          </svg>
          FaunaFond
        </a>
        <div class="flex gap-6 flex-wrap">
          <a href="#" class="text-[#0e2c20] no-underline text-sm opacity-70 hover:opacity-100 transition-opacity">Privaatsuspoliitika</a>
          <a href="#" class="text-[#0e2c20] no-underline text-sm opacity-70 hover:opacity-100 transition-opacity">Kasutustingimused</a>
          <a href="#" class="text-[#0e2c20] no-underline text-sm opacity-70 hover:opacity-100 transition-opacity">Kontakt</a>
        </div>
      </div>
      <div class="max-w-[1160px] mx-auto px-8 text-center text-[13px] opacity-50 pt-6">
        © 2024 FaunaFond. Kõik õigused kaitstud.
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'

const mobileOpen = ref(false)

const problems = [
  {
    title: 'Killustatud andmed',
    desc: 'Kliinikud haldavad andmeid eraldi infosüsteemides. Terviselugu on laiali ning puudub ühtne pilt lemmiku tervisest ja ajaloost.',
    icon: `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>`
  },
  {
    title: 'Kadunud paberid',
    desc: 'Paberkandjal vaktsineerimispassid kipuvad kaduma või ununema, piirates ligipääsu olulisele infole reisimisel või kolimisel.',
    icon: `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><path d="M10 10.3c.2-.4.5-.8.9-1a2.1 2.1 0 0 1 2.6.4c.3.4.5.8.5 1.3 0 1.3-2 2-2 2"/><path d="M12 17h.01"/></svg>`
  },
  {
    title: 'Ununenud toimingud',
    desc: 'Ilma automaatsete meeldetuletusteta on lihtne unustada vaktsiinide aegumist või ravimite õigeaegset manustamist.',
    icon: `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="m9 16 2 2 4-4"/></svg>`
  }
]

const feature1List = [
  'Detailne profiiliinfo ja pildid',
  'Kiire ligipääs ja haldamine igast seadmest'
]

const feature2List = [
  'Vaktsiinide ja ravimite andmebaas',
  'Automaatsed värvikoodidega meeldetuletused',
  'Arstivisiitide detailsed märkmed ja ajalugu'
]
</script>