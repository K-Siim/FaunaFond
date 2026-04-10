<template>
  <div class="ff-root">
    <!-- NAV -->
    <nav class="ff-nav">
      <div class="ff-container ff-nav__inner">
        <a href="#" class="ff-logo">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/>
            <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>
          </svg>
          FaunaFond
        </a>
        <div class="ff-nav__links">
          <a href="#probleem">Probleem</a>
          <a href="#funktsioonid">Funktsioonid</a>
          <a href="#kuidas">Kuidas töötab</a>
        </div>
        <a href="#" class="ff-btn ff-btn--primary ff-btn--sm">Logi sisse</a>
        <button class="ff-nav__burger" @click="mobileOpen = !mobileOpen" :aria-expanded="mobileOpen">
          <span></span><span></span><span></span>
        </button>
      </div>
      <div class="ff-nav__mobile" :class="{ 'is-open': mobileOpen }">
        <a href="#probleem" @click="mobileOpen = false">Probleem</a>
        <a href="#funktsioonid" @click="mobileOpen = false">Funktsioonid</a>
        <a href="#kuidas" @click="mobileOpen = false">Kuidas töötab</a>
        <a href="#" class="ff-btn ff-btn--primary">Logi sisse</a>
      </div>
    </nav>

    <main>
      <!-- HERO -->
      <section class="ff-hero">
        <div class="ff-hero__bg-blob"></div>
        <div class="ff-container ff-hero__grid">
          <div class="ff-hero__content">
            <div class="ff-hero__eyebrow">
              <span class="ff-dot"></span> Digitaalne tervisepass lemmikloomadele
            </div>
            <h1 class="ff-hero__heading">
              Lemmiklooma tervis,<br>
              <em>alati sinuga</em> kaasas.
            </h1>
            <p class="ff-hero__sub">
              FaunaFond on mugav ja turvaline digitaalne tervisepass sinu lemmikloomale.
              Unusta paberimajandus ja hoia vaktsiinid, ravimid ning arstivisiidid alati
              käepärast ühes rakenduses.
            </p>
            <div class="ff-hero__actions">
              <a href="#" class="ff-btn ff-btn--primary ff-btn--lg">Alusta tasuta</a>
              <a href="#kuidas" class="ff-btn ff-btn--ghost ff-btn--lg">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8"/></svg>
                Kuidas see töötab?
              </a>
            </div>
          </div>

          <!-- PHONE MOCKUP -->
          <div class="ff-hero__visual" aria-hidden="true">
            <div class="ff-mockup">
              <div class="ff-mockup__notch"></div>
              <div class="ff-mockup__screen">
                <div class="ff-mockup__topbar">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                </div>
                <h3 class="ff-mockup__greeting">Tere, Kasutaja 👋</h3>

                <div class="ff-mockup__section">
                  <div class="ff-mockup__section-header">
                    <span>Lemmikute nimekiri</span>
                    <span class="ff-mockup__link">Vaata kõiki</span>
                  </div>
                  <div class="ff-mockup__pet-card">
                    <img src="https://storage.googleapis.com/banani-generated-images/generated-images/0820d631-bbf7-4358-8282-bfb610055f9c.jpg" alt="Milli-Malli" />
                    <div>
                      <strong>Milli-Malli</strong>
                      <span>Kass · 17 aastat</span>
                    </div>
                  </div>
                  <div class="ff-mockup__pet-card">
                    <img src="https://storage.googleapis.com/banani-generated-images/generated-images/0692e44a-b94a-4066-9b45-00ffac9c27a2.jpg" alt="Lota" />
                    <div>
                      <strong>Lota</strong>
                      <span>Koer · 1 aasta · 46 kg</span>
                    </div>
                  </div>
                </div>

                <div class="ff-mockup__section">
                  <div class="ff-mockup__section-header">
                    <span>Meeldetuletused</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                  </div>
                  <div class="ff-alert ff-alert--red">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>"Milli-Malli" marutaudi vaktsiin aegub "4. jaanuaril 2026"</span>
                  </div>
                  <div class="ff-alert ff-alert--yellow">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    <span>"Lota" vaktsineerimise kuupäev on "15. oktoober 2025"</span>
                  </div>
                  <div class="ff-alert ff-alert--blue">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span>"Lota" arstivisiidi kuupäev on "15. oktoober 2025"</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- PROBLEM -->
      <section class="ff-problem" id="probleem">
        <div class="ff-container">
          <div class="ff-section-header">
            <p class="ff-section-label">Probleemid</p>
            <h2>Miks vajad ühtset tervisepassi?</h2>
            <p>Lemmiklooma tervise eest hoolitsemine nõuab omanikult täpsust. Killustatud info võib viia vigade ja asjatu stressini.</p>
          </div>
          <div class="ff-problem__grid">
            <div v-for="p in problems" :key="p.title" class="ff-problem__card">
              <div class="ff-problem__icon" v-html="p.icon"></div>
              <h3>{{ p.title }}</h3>
              <p>{{ p.desc }}</p>
            </div>
          </div>
        </div>
      </section>

      <!-- FEATURES -->
      <section class="ff-features" id="funktsioonid">
        <div class="ff-container">
          <!-- Feature 1 -->
          <div class="ff-feature-row">
            <div class="ff-feature__content">
              <div class="ff-badge">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Mitme lemmiku haldus
              </div>
              <h2>Kõik sinu lemmikud ühes turvalises kohas</h2>
              <p>Lisa rakendusse piiramatult lemmikloomi. Igal loomal on oma isiklik profiil, mis koondab olulised andmed kiibi numbrist kuni kaalu ja tõuni.</p>
              <ul class="ff-feature__list">
                <li v-for="item in feature1List" :key="item">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                  {{ item }}
                </li>
              </ul>
            </div>
            <div class="ff-feature__visual">
              <img
                src="https://storage.googleapis.com/banani-generated-images/generated-images/41d03c18-68f1-4a97-8817-86175e272465.jpg"
                alt="Lemmikud koos"
                class="ff-feature__img"
              />
            </div>
          </div>

          <!-- Feature 2 -->
          <div class="ff-feature-row ff-feature-row--reverse" id="kuidas">
            <div class="ff-feature__content">
              <div class="ff-badge">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                Terviselugu ja profiil
              </div>
              <h2>Täielik ülevaade meditsiinilisest ajaloost</h2>
              <p>Jälgi vaktsineerimisi, ravimikuure ja arstivisiitide logi. Süsteem hoiatab sind lähenevatest tähtaegadest nutikate värvikoodidega meeldetuletuste abil.</p>
              <ul class="ff-feature__list">
                <li v-for="item in feature2List" :key="item">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                  {{ item }}
                </li>
              </ul>
            </div>
            <div class="ff-feature__visual ff-feature__visual--mockup">
              <div class="ff-mockup ff-mockup--sm">
                <div class="ff-mockup__notch ff-mockup__notch--sm"></div>
                <div class="ff-mockup__screen">
                  <div class="ff-mockup__topbar">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                  </div>
                  <div class="ff-mockup__pet-header">
                    <img src="https://storage.googleapis.com/banani-generated-images/generated-images/0820d631-bbf7-4358-8282-bfb610055f9c.jpg" alt="Milli-Malli" />
                    <h3>Milli-Malli</h3>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                  </div>
                  <div class="ff-mockup__info-table">
                    <div><span>Kiip</span><strong>****</strong></div>
                    <div><span>Liik</span><strong>Kass</strong></div>
                    <div><span>Tõug</span><strong>Segavereline</strong></div>
                    <div><span>Sugu</span><strong>Emane</strong></div>
                    <div><span>Vanus</span><strong>06.10.2008 (17)</strong></div>
                  </div>
                  <div class="ff-mockup__section" style="margin-top:16px">
                    <div class="ff-mockup__section-header">
                      <span>Meditsiiniline info</span>
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                    </div>
                    <div class="ff-mockup__med-block">
                      <div class="ff-mockup__med-title">Vaktsiinid</div>
                      <div class="ff-mockup__med-row">
                        <span>💉 Eurican CHPPi2-L</span><span>16.02.2014</span>
                      </div>
                      <div class="ff-mockup__med-row">
                        <span>💉 Rabisin</span><span>23.07.2015</span>
                      </div>
                      <div class="ff-mockup__med-title" style="margin-top:12px">Ravimid</div>
                      <div class="ff-mockup__med-row">
                        <span>💊 Thiamacare (10mg/ml)</span><span>5ml, 2x</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- CTA -->
      <section class="ff-cta">
        <div class="ff-cta__bg-pattern"></div>
        <div class="ff-container ff-cta__inner">
          <div class="ff-cta__paw" aria-hidden="true">
            <svg width="80" height="80" viewBox="0 0 24 24" fill="currentColor"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
          </div>
          <h2>Loo alus oma lemmiku paremale tervisele</h2>
          <p>Liitu FaunaFondiga juba täna. Progressiivne veebirakendus töötab sujuvalt igas seadmes, tagades ligipääsu andmetele igal ajal ja igas kohas.</p>
          <a href="#" class="ff-btn ff-btn--light ff-btn--lg">Loo tasuta konto &rarr;</a>
        </div>
      </section>
    </main>

    <!-- FOOTER -->
    <footer class="ff-footer">
      <div class="ff-container ff-footer__inner">
        <a href="#" class="ff-logo">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/>
            <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>
          </svg>
          FaunaFond
        </a>
        <div class="ff-footer__links">
          <a href="#">Privaatsuspoliitika</a>
          <a href="#">Kasutustingimused</a>
          <a href="#">Kontakt</a>
        </div>
      </div>
      <div class="ff-container ff-footer__copy">
        © 2024 FaunaFond. Kõik õigused kaitstud.
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref } from 'vue'

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

<style scoped>
/* ── TOKENS ─────────────────────────────────────────────── */
.ff-root {
  --bg:      #e8f0e5;
  --fg:      #0e2c20;
  --card:    #fffdf5;
  --primary: #275342;
  --primary-fg: #fffdf5;
  --border:  rgba(39,83,66,.14);
  --alert-r: #ffcbc7;
  --alert-y: #fff6ca;
  --alert-b: #d5eeff;
  --r-sm: 8px;
  --r-md: 14px;
  --r-lg: 24px;
  --r-xl: 36px;
  --font-display: 'DM Serif Display', Georgia, serif;
  --font-body:    'DM Sans', system-ui, sans-serif;

  font-family: var(--font-body);
  background: var(--bg);
  color: var(--fg);
  line-height: 1.5;
  -webkit-font-smoothing: antialiased;
  overflow-x: hidden;
}

@import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&family=DM+Serif+Display:ital@0;1&display=swap');

/* ── CONTAINER ──────────────────────────────────────────── */
.ff-container {
  max-width: 1160px;
  margin: 0 auto;
  padding: 0 32px;
}

/* ── NAV ────────────────────────────────────────────────── */
.ff-nav {
  position: sticky;
  top: 0;
  z-index: 100;
  background: rgba(232,240,229,.88);
  backdrop-filter: blur(16px);
  border-bottom: 1px solid var(--border);
}
.ff-nav__inner {
  display: flex;
  align-items: center;
  gap: 32px;
  padding-top: 18px;
  padding-bottom: 18px;
}
.ff-logo {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 20px;
  font-weight: 700;
  color: var(--primary);
  text-decoration: none;
  flex-shrink: 0;
}
.ff-nav__links {
  display: flex;
  gap: 28px;
  margin-left: auto;
}
.ff-nav__links a {
  color: var(--fg);
  text-decoration: none;
  font-size: 15px;
  font-weight: 500;
  opacity: .8;
  transition: opacity .2s;
}
.ff-nav__links a:hover { opacity: 1; }
.ff-nav__burger {
  display: none;
  flex-direction: column;
  gap: 5px;
  background: none;
  border: none;
  cursor: pointer;
  padding: 4px;
  margin-left: auto;
}
.ff-nav__burger span {
  display: block;
  width: 22px;
  height: 2px;
  background: var(--fg);
  border-radius: 2px;
}
.ff-nav__mobile {
  display: none;
  flex-direction: column;
  gap: 16px;
  padding: 20px 32px 24px;
  border-top: 1px solid var(--border);
}
.ff-nav__mobile.is-open { display: flex; }
.ff-nav__mobile a {
  color: var(--fg);
  text-decoration: none;
  font-size: 16px;
  font-weight: 500;
}

/* ── BUTTONS ────────────────────────────────────────────── */
.ff-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  text-decoration: none;
  font-family: var(--font-body);
  font-weight: 600;
  border-radius: 100px;
  border: none;
  cursor: pointer;
  transition: transform .15s, box-shadow .15s;
  white-space: nowrap;
}
.ff-btn:hover { transform: translateY(-1px); }
.ff-btn--primary {
  background: var(--primary);
  color: var(--primary-fg);
  box-shadow: 0 4px 16px rgba(39,83,66,.25);
}
.ff-btn--primary:hover { box-shadow: 0 6px 24px rgba(39,83,66,.35); }
.ff-btn--ghost {
  background: transparent;
  color: var(--fg);
  border: 1.5px solid var(--border);
}
.ff-btn--light {
  background: var(--card);
  color: var(--fg);
  box-shadow: 0 4px 20px rgba(0,0,0,.1);
}
.ff-btn--sm  { padding: 10px 22px; font-size: 14px; }
.ff-btn--lg  { padding: 18px 36px; font-size: 16px; }

/* ── HERO ───────────────────────────────────────────────── */
.ff-hero {
  position: relative;
  padding: 96px 0 120px;
  overflow: hidden;
}
.ff-hero__bg-blob {
  position: absolute;
  top: -100px;
  right: -150px;
  width: 700px;
  height: 700px;
  background: radial-gradient(circle, rgba(39,83,66,.12) 0%, transparent 70%);
  border-radius: 50%;
  pointer-events: none;
}
.ff-hero__grid {
  display: grid;
  grid-template-columns: 1.1fr 0.9fr;
  gap: 80px;
  align-items: center;
}
.ff-hero__eyebrow {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-size: 13px;
  font-weight: 600;
  letter-spacing: .04em;
  text-transform: uppercase;
  color: var(--primary);
  opacity: .8;
  margin-bottom: 20px;
}
.ff-dot {
  width: 8px;
  height: 8px;
  background: var(--primary);
  border-radius: 50%;
  display: inline-block;
}
.ff-hero__heading {
  font-family: var(--font-display);
  font-size: clamp(40px, 5vw, 60px);
  line-height: 1.08;
  letter-spacing: -.5px;
  margin-bottom: 24px;
}
.ff-hero__heading em {
  font-style: italic;
  color: var(--primary);
}
.ff-hero__sub {
  font-size: 18px;
  line-height: 1.65;
  opacity: .75;
  margin-bottom: 40px;
  max-width: 480px;
}
.ff-hero__actions {
  display: flex;
  gap: 14px;
  flex-wrap: wrap;
}

/* ── PHONE MOCKUP ───────────────────────────────────────── */
.ff-hero__visual {
  display: flex;
  justify-content: center;
}
.ff-mockup {
  width: 300px;
  background: var(--bg);
  border: 10px solid var(--fg);
  border-radius: 44px;
  overflow: hidden;
  box-shadow: 0 40px 80px rgba(14,44,32,.2), 0 0 0 1px rgba(0,0,0,.05);
  position: relative;
  flex-shrink: 0;
}
.ff-mockup--sm {
  width: 280px;
}
.ff-mockup__notch {
  width: 100px;
  height: 24px;
  background: var(--fg);
  border-radius: 0 0 18px 18px;
  margin: 0 auto 4px;
}
.ff-mockup__notch--sm {
  width: 80px;
  height: 20px;
}
.ff-mockup__screen {
  padding: 8px 16px 20px;
  overflow: hidden;
}
.ff-mockup__topbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 14px;
  color: var(--fg);
}
.ff-mockup__greeting {
  font-size: 18px;
  font-weight: 700;
  margin-bottom: 18px;
}
.ff-mockup__section {
  margin-bottom: 18px;
}
.ff-mockup__section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 12px;
  font-weight: 700;
  margin-bottom: 10px;
  color: var(--fg);
}
.ff-mockup__link {
  font-size: 11px;
  opacity: .6;
  font-weight: 500;
  cursor: pointer;
}
.ff-mockup__pet-card {
  background: var(--card);
  border-radius: var(--r-md);
  padding: 10px 12px;
  display: flex;
  gap: 12px;
  align-items: center;
  margin-bottom: 8px;
}
.ff-mockup__pet-card img {
  width: 48px;
  height: 48px;
  border-radius: 10px;
  object-fit: cover;
  flex-shrink: 0;
}
.ff-mockup__pet-card div {
  display: flex;
  flex-direction: column;
  gap: 2px;
}
.ff-mockup__pet-card strong { font-size: 13px; }
.ff-mockup__pet-card span   { font-size: 11px; opacity: .7; }
.ff-alert {
  display: flex;
  gap: 10px;
  align-items: flex-start;
  padding: 10px 12px;
  border-radius: var(--r-md);
  font-size: 11px;
  line-height: 1.4;
  font-weight: 500;
  margin-bottom: 8px;
}
.ff-alert svg { flex-shrink: 0; margin-top: 1px; }
.ff-alert--red    { background: var(--alert-r); }
.ff-alert--yellow { background: var(--alert-y); }
.ff-alert--blue   { background: var(--alert-b); }
.ff-mockup__pet-header {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 16px;
  margin-top: 8px;
}
.ff-mockup__pet-header img {
  width: 56px;
  height: 56px;
  border-radius: 12px;
  object-fit: cover;
  flex-shrink: 0;
}
.ff-mockup__pet-header h3 {
  flex: 1;
  font-size: 17px;
  font-weight: 700;
  margin: 0;
}
.ff-mockup__pet-header svg { color: var(--primary); flex-shrink: 0; }
.ff-mockup__info-table {
  background: var(--card);
  border-radius: var(--r-md);
  padding: 12px 14px;
  display: flex;
  flex-direction: column;
  gap: 9px;
  font-size: 11px;
}
.ff-mockup__info-table div {
  display: flex;
  justify-content: space-between;
}
.ff-mockup__info-table span { opacity: .7; }
.ff-mockup__med-block {
  background: var(--card);
  border-radius: var(--r-md);
  padding: 12px 14px;
  margin-top: 8px;
}
.ff-mockup__med-title {
  font-size: 11px;
  font-weight: 700;
  margin-bottom: 8px;
}
.ff-mockup__med-row {
  display: flex;
  justify-content: space-between;
  font-size: 10px;
  margin-bottom: 6px;
  opacity: .85;
}

/* ── PROBLEM SECTION ────────────────────────────────────── */
.ff-problem {
  background: var(--card);
  padding: 112px 0;
}
.ff-section-header {
  text-align: center;
  max-width: 660px;
  margin: 0 auto 64px;
}
.ff-section-label {
  font-size: 12px;
  font-weight: 700;
  letter-spacing: .1em;
  text-transform: uppercase;
  color: var(--primary);
  opacity: .7;
  margin-bottom: 12px;
}
.ff-section-header h2 {
  font-family: var(--font-display);
  font-size: clamp(30px, 4vw, 42px);
  margin-bottom: 16px;
  line-height: 1.1;
}
.ff-section-header p {
  font-size: 17px;
  opacity: .75;
  line-height: 1.65;
}
.ff-problem__grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 28px;
}
.ff-problem__card {
  background: var(--bg);
  padding: 40px 36px;
  border-radius: var(--r-lg);
  transition: transform .2s;
}
.ff-problem__card:hover { transform: translateY(-4px); }
.ff-problem__icon {
  width: 48px;
  height: 48px;
  background: rgba(39,83,66,.1);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 20px;
  color: var(--primary);
}
.ff-problem__card h3 {
  font-size: 18px;
  font-weight: 700;
  margin-bottom: 10px;
}
.ff-problem__card p {
  font-size: 14px;
  opacity: .75;
  line-height: 1.6;
}

/* ── FEATURES ───────────────────────────────────────────── */
.ff-features {
  padding: 48px 0;
}
.ff-feature-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 80px;
  align-items: center;
  padding: 80px 0;
}
.ff-feature-row + .ff-feature-row {
  border-top: 1px solid var(--border);
}
.ff-feature-row--reverse .ff-feature__content { order: 1; }
.ff-feature-row--reverse .ff-feature__visual   { order: -1; }
.ff-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: rgba(39,83,66,.1);
  color: var(--primary);
  padding: 6px 16px;
  border-radius: 100px;
  font-size: 13px;
  font-weight: 700;
  margin-bottom: 24px;
}
.ff-feature__content h2 {
  font-family: var(--font-display);
  font-size: clamp(28px, 3.5vw, 40px);
  line-height: 1.1;
  margin-bottom: 20px;
}
.ff-feature__content p {
  font-size: 17px;
  opacity: .75;
  line-height: 1.65;
  margin-bottom: 28px;
}
.ff-feature__list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 14px;
}
.ff-feature__list li {
  display: flex;
  align-items: center;
  gap: 12px;
  font-size: 15px;
  font-weight: 500;
}
.ff-feature__list li svg { color: var(--primary); flex-shrink: 0; }
.ff-feature__img {
  width: 100%;
  border-radius: var(--r-xl);
  object-fit: cover;
  box-shadow: 0 24px 48px rgba(14,44,32,.12);
  aspect-ratio: 4/3;
}
.ff-feature__visual--mockup {
  display: flex;
  justify-content: center;
}

/* ── CTA ────────────────────────────────────────────────── */
.ff-cta {
  background: var(--primary);
  color: var(--primary-fg);
  padding: 120px 0;
  text-align: center;
  position: relative;
  overflow: hidden;
}
.ff-cta__bg-pattern {
  position: absolute;
  inset: 0;
  background-image: radial-gradient(circle at 20% 50%, rgba(255,255,255,.06) 0%, transparent 50%),
                    radial-gradient(circle at 80% 20%, rgba(255,255,255,.05) 0%, transparent 40%);
  pointer-events: none;
}
.ff-cta__inner { position: relative; max-width: 700px; margin: 0 auto; }
.ff-cta__paw {
  opacity: .12;
  margin-bottom: 24px;
}
.ff-cta__paw svg { display: block; margin: 0 auto; }
.ff-cta h2 {
  font-family: var(--font-display);
  font-size: clamp(32px, 5vw, 50px);
  line-height: 1.1;
  margin-bottom: 20px;
}
.ff-cta p {
  font-size: 18px;
  opacity: .85;
  line-height: 1.65;
  margin-bottom: 40px;
}

/* ── FOOTER ─────────────────────────────────────────────── */
.ff-footer {
  background: var(--card);
  padding: 56px 0 28px;
}
.ff-footer__inner {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-bottom: 40px;
  border-bottom: 1px solid var(--border);
  flex-wrap: wrap;
  gap: 20px;
}
.ff-footer__links {
  display: flex;
  gap: 24px;
  flex-wrap: wrap;
}
.ff-footer__links a {
  color: var(--fg);
  text-decoration: none;
  font-size: 14px;
  opacity: .7;
  transition: opacity .2s;
}
.ff-footer__links a:hover { opacity: 1; }
.ff-footer__copy {
  text-align: center;
  font-size: 13px;
  opacity: .5;
  padding-top: 24px;
}

/* ── RESPONSIVE ─────────────────────────────────────────── */
@media (max-width: 900px) {
  .ff-hero__grid,
  .ff-feature-row,
  .ff-feature-row--reverse {
    grid-template-columns: 1fr;
    gap: 48px;
  }
  .ff-feature-row--reverse .ff-feature__content,
  .ff-feature-row--reverse .ff-feature__visual { order: unset; }
  .ff-hero__visual { display: none; }
  .ff-problem__grid { grid-template-columns: 1fr; }
}
@media (max-width: 680px) {
  .ff-container { padding: 0 20px; }
  .ff-nav__links { display: none; }
  .ff-nav__inner > .ff-btn { display: none; }
  .ff-nav__burger { display: flex; }
  .ff-problem__grid { grid-template-columns: 1fr; }
  .ff-hero { padding: 64px 0 80px; }
}
</style>