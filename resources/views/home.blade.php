<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>E-mebel</title>
<script src="https://cdn.tailwindcss.com/3.4.16"></script>
<script>
tailwind.config={
theme:{
extend:{
colors:{
primary:'#98A898',
secondary:'#6D8764'
},
borderRadius:{
'none':'0px',
'sm':'4px',
DEFAULT:'8px',
'md':'12px',
'lg':'16px',
'xl':'20px',
'2xl':'24px',
'3xl':'32px',
'full':'9999px',
'button':'8px'
}
}
}
}
</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
<style>
:where([class^="ri-"])::before {
content: "\f3c2";
}
body {
font-family: 'Inter', sans-serif;
}
.nav-link {
position: relative;
transition: all 0.3s ease;
}
.nav-link:after {
content: '';
position: absolute;
width: 0;
height: 2px;
background-color: #98A898;
bottom: -4px;
left: 0;
transition: width 0.3s ease;
}
.nav-link:hover:after {
width: 100%;
}
.nav-link.active:after {
width: 100%;
}
html {
  scroll-behavior: smooth;
}

</style>
</head>
<body class="bg-white">
<header class="w-full bg-white py-4 px-6 md:px-12 flex items-center justify-between">
<a href="#" class="text-3xl font-['Pacifico'] text-primary">E mebel</a>
<nav class="hidden md:flex items-center space-x-8">
<a href="#" class="nav-link text-gray-800 hover:text-primary">Home</a>
<a href="#about" class="nav-link text-gray-800 hover:text-primary transition-all">About</a> 

<a href="#contact" class="nav-link text-gray-800 hover:text-primary">Contact</a>
</nav>
<a href="{{ route('login') }}" class="hidden md:block bg-primary text-white py-2 px-6 !rounded-button whitespace-nowrap hover:bg-opacity-90 transition-all">Get Started</a>
<button class="md:hidden w-10 h-10 flex items-center justify-center">
<i class="ri-menu-line text-xl"></i>
</button>
</header>
<main>
<section class="w-full min-h-[600px] flex flex-col md:flex-row items-center px-6 md:px-12 py-16 md:py-24">
<div class="w-full md:w-1/2 pr-0 md:pr-12 mb-12 md:mb-0">
<h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6">Emebel</h1>
<p class="text-gray-600 text-lg mb-8">
Jelajahi keperluan furnitur rumah tangga anda, butuh mebel atau furniture? Yaaa Emebel Jagonyaa................
</p>
<div class="flex flex-wrap gap-4">
<a href="{{ route('login') }}" class="bg-primary text-white py-3 px-8 !rounded-button whitespace-nowrap hover:bg-opacity-90 transition-all">Get Started</a>
<a href="{{ route('register') }}" class="border border-gray-300 text-gray-700 py-3 px-8 !rounded-button whitespace-nowrap hover:border-primary hover:text-primary transition-all">Register</a>
</div>
</div>
<div class="w-full md:w-1/2">
<img src="https://readdy.ai/api/search-image?query=A%20modern%2C%20minimalist%20workspace%20with%20natural%20elements.%20A%20wooden%20desk%20with%20a%20computer%20monitor%2C%20surrounded%20by%20indoor%20plants.%20Warm%20sunlight%20streaming%20through%20windows%20casting%20soft%20shadows%20on%20a%20clean%20white%20wall.%20The%20scene%20conveys%20a%20peaceful%2C%20productive%20environment%20with%20organic%20design%20elements.&width=800&height=600&seq=123456&orientation=landscape"
alt="Nature-inspired workspace"
class="w-full h-auto rounded-2xl shadow-lg object-cover object-top">
</div>
</section>
<section id="about" class="bg-gray-50 py-20 px-6 md:px-12">

  <div class="text-center mb-16">
    <h2 class="text-4xl font-bold mb-4">About Us</h2>
    <p class="text-gray-600 max-w-2xl mx-auto">
      Proyek ini dibuat untuk memenuhi tugas project kampus sekaligus
      menjadi dasar pengembangan E-mebel.
    </p>
  </div>

  <!-- OWNER SECTION (MOBILE STYLE) -->
  <div class="max-w-4xl mx-auto mb-24">
    <div class="bg-primary/10 p-10 rounded-3xl text-center shadow-md">

      <!-- Badge -->
      <div class="flex justify-center items-center gap-2 mb-6">
        <i class="ri-award-line text-primary"></i>
        <span class="text-primary text-sm font-semibold uppercase tracking-widest">
          Trusted By
        </span>
      </div>

      <!-- Avatar -->
      <div class="relative inline-block mb-6">
        <div class="w-32 h-32 rounded-full border-4 border-primary p-1">
          <img src="{{ asset('images/Ownerr.jpg') }}"
               class="w-full h-full rounded-full object-cover">
        </div>
        <div class="absolute bottom-2 right-2 w-7 h-7 bg-primary rounded-full flex items-center justify-center border-2 border-white">
          <i class="ri-check-line text-white text-sm"></i>
        </div>
      </div>

      <p class="text-xs uppercase tracking-widest text-gray-500 mb-1">
        Proudly Owned By
      </p>
      <h3 class="text-3xl font-bold mb-4">Salman Property</h3>

      <!-- Tags -->
      <div class="flex justify-center gap-3 mb-6 flex-wrap">
        <span class="bg-white px-4 py-1 rounded-full shadow text-sm flex items-center gap-2">
          <i class="ri-home-4-line text-primary"></i> Property Expert
        </span>
        <span class="bg-white px-4 py-1 rounded-full shadow text-sm flex items-center gap-2">
          <i class="ri-star-line text-primary"></i> 10+ Years
        </span>
      </div>

      <p class="text-gray-600 max-w-xl mx-auto mb-8">
        Menyediakan solusi furnitur berkualitas premium dengan dedikasi
        penuh terhadap kepuasan pelanggan.
      </p>

      <!-- Stats -->
      <div class="grid grid-cols-3 border-t border-primary/20 pt-6">
        <div>
          <h4 class="text-2xl font-bold text-primary">1000+</h4>
          <p class="text-sm text-gray-500">Happy Clients</p>
        </div>
        <div>
          <h4 class="text-2xl font-bold text-primary">500+</h4>
          <p class="text-sm text-gray-500">Products</p>
        </div>
        <div>
          <h4 class="text-2xl font-bold text-primary">98%</h4>
          <p class="text-sm text-gray-500">Satisfaction</p>
        </div>
      </div>

    </div>
  </div>

  <!-- TEAM -->
  <div class="text-center mb-10">
    <h3 class="text-3xl font-bold mb-4">Meet Our Team</h3>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
    <div class="bg-white p-8 rounded-2xl shadow">
      <h4 class="font-bold mb-2">Web Developer</h4>
      <p class="text-gray-600">Putri Ayu Fadhillah</p>
    </div>
    <div class="bg-white p-8 rounded-2xl shadow">
      <h4 class="font-bold mb-2">Mobile Developer</h4>
      <p class="text-gray-600">Khoerul Paroid</p>
    </div>
    <div class="bg-white p-8 rounded-2xl shadow">
      <h4 class="font-bold mb-2">All Role</h4>
      <p class="text-gray-600">Pahril Lesmana</p>
    </div>
  </div>

</section>
<section class="w-full px-6 md:px-12 py-20 bg-white">
  <div class="text-center mb-16">
    <h3 class="text-3xl md:text-4xl font-bold mb-4">Mau Tau Kami?</h3>
    <p class="text-gray-600 max-w-2xl mx-auto">Kenalan dulu sama tim kece dibalik E-mebel</p>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-10 max-w-6xl mx-auto">

    <!-- Card -->
    <div class="group relative rounded-3xl overflow-hidden shadow-md bg-white hover:shadow-xl transition-all duration-300">
      <img src="{{ asset('images/amo.jpg') }}" class="w-full h-72 object-cover transition-transform duration-500 group-hover:scale-110">
      <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300"></div>

      <div class="absolute bottom-6 left-1/2 -translate-x-1/2 text-center text-white opacity-0 group-hover:opacity-100 transition-all duration-300">
        <h3 class="text-xl font-semibold">Khoerul Paroid</h3>
        <p class="text-sm">Mobile Developer</p>
      </div>
    </div>

    <!-- Card -->
    <div class="group relative rounded-3xl overflow-hidden shadow-md bg-white hover:shadow-xl transition-all duration-300">
      <img src="{{ asset('images/puyu cantik.jpg') }}" class="w-full h-72 object-cover transition-transform duration-500 group-hover:scale-110">
      <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300"></div>

      <div class="absolute bottom-6 left-1/2 -translate-x-1/2 text-center text-white opacity-0 group-hover:opacity-100 transition-all duration-300">
        <h3 class="text-xl font-semibold">Putri Ayu Fadhillah</h3>
        <p class="text-sm">Web Developer</p>
      </div>
    </div>

    <!-- Card -->
    <div class="group relative rounded-3xl overflow-hidden shadow-md bg-white hover:shadow-xl transition-all duration-300">
      <img src="{{ asset('images/pahril2.jpg') }}" class="w-full h-72 object-cover transition-transform duration-500 group-hover:scale-110">
      <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300"></div>

      <div class="absolute bottom-6 left-1/2 -translate-x-1/2 text-center text-white opacity-0 group-hover:opacity-100 transition-all duration-300">
        <h3 class="text-xl font-semibold">Pahril Lesmana</h3>
        <p class="text-sm">All Role</p>
      </div>
    </div>

  </div>
</section>

        <div class="md:col-span-2">
    <div
      id="successMessage"
      class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
      <div class="bg-white p-8 rounded-2xl max-w-md w-full mx-4">
        <div class="text-center">
          <div
            class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4"
          >
            <i class="ri-check-line text-3xl text-green-500"></i>
          </div>
          <h3 class="text-xl font-semibold mb-2">
            Message Sent Successfully!
          </h3>
          <p class="text-gray-600 mb-6">
            Thank you for reaching out. We'll get back to you shortly.
          </p>
          <button
            onclick="closeSuccessMessage()"
            class="bg-primary text-white py-2 px-6 !rounded-button whitespace-nowrap hover:bg-opacity-90 transition-all"
          >
            Close
          </button>
        </div>
      </div>
    </div>
  </div>
</section>
<footer id="contact" class="w-full bg-primary/5 text-gray-800 px-6 md:px-12 pt-16 pb-8">
  <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
    <div>
      <a
        href="{{ url('/') }}"
        class="text-3xl font-['Pacifico'] text-primary mb-4 block"
        >E mebel</a
      >
      <p class="text-gray-600">
        Kenyamanan anda prioritas kami, 
        kepuasan anda tujuan kami.
      </p>
    </div>
    <div>
      <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
      <ul class="space-y-2">
        <li>
          <a
            href="#about"
            class="text-gray-600 hover:text-primary transition-colors"
            >About</a
          >
        </li>
        <li>
          <a
            href="#contact"
            class="text-gray-600 hover:text-primary transition-colors"
            >Contact</a
          >
        </li>
      </ul>
    </div>
    <div>
      <h4 class="text-lg font-semibold mb-4">Services</h4>
      <ul class="space-y-2">
        <li>
          <a
            href="#"
            class="text-gray-600 hover:text-primary transition-colors"
            >Web Design</a
          >
        </li>
        <li>
          <a
            href="#"
            class="text-gray-600 hover:text-primary transition-colors"
            >Development</a
          >
        </li>
        <li>
          <a
            href="#"
            class="text-gray-600 hover:text-primary transition-colors"
            >Digital Strategy</a
          >
        </li>
        <li>
          <a
            href="#"
            class="text-gray-600 hover:text-primary transition-colors"
            >Branding</a
          >
        </li>
      </ul>
    </div>
    <div>
      <h4 class="text-lg font-semibold mb-4">Contact Us</h4>
      <ul class="space-y-2">
        <li class="flex items-center gap-2">
          <i class="ri-mail-line"></i>
          <a
            href="mailto:hello@example.com"
            class="text-gray-600 hover:text-primary transition-colors"
            >emebel.properti@gmail.com</a
          >
        </li>
        <li class="flex items-center gap-2">
          <i class="ri-phone-line"></i>
          <a
            href="tel:+1234567890"
            class="text-gray-600 hover:text-primary transition-colors"
            >+62 813-1139-4644</a
          >
        </li>
      </ul>
    </div>
  </div>
  <div class="border-t border-gray-200 pt-8">
    <div
      class="flex flex-col md:flex-row justify-between items-center gap-4"
    >
      <p class="text-gray-600 text-sm">
        &copy; 2025 Emebel. departement.
      </p>
      <div class="flex items-center gap-4">
        <a
          href="#"
          class="w-8 h-8 flex items-center justify-center rounded-full bg-primary/10 hover:bg-primary/20 text-primary transition-colors"
        >
          <i class="ri-facebook-fill"></i>
        </a>
        <a
          href="#"
          class="w-8 h-8 flex items-center justify-center rounded-full bg-primary/10 hover:bg-primary/20 text-primary transition-colors"
        >
          <i class="ri-twitter-fill"></i>
        </a>
        <a
          href="#"
          class="w-8 h-8 flex items-center justify-center rounded-full bg-primary/10 hover:bg-primary/20 text-primary transition-colors"
        >
          <i class="ri-instagram-fill"></i>
        </a>
        <a
          href="#"
          class="w-8 h-8 flex items-center justify-center rounded-full bg-primary/10 hover:bg-primary/20 text-primary transition-colors"
        >
          <i class="ri-linkedin-fill"></i>
        </a>
      </div>
    </div>
  </div>
</footer>
<script>
  const menuBtn = document.querySelector('button.md\\:hidden');
  const nav = document.querySelector('nav');

  menuBtn.addEventListener('click', () => {
    nav.classList.toggle('hidden');
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const mobileMenuButton = document.querySelector('.md\\:hidden');
  const nav = document.querySelector('nav');
  const getStartedButton = document.querySelector('header a[href*="get-started"]');
  
  let isMenuOpen = false;
  
  function toggleMenu() {
    isMenuOpen = !isMenuOpen;
    
    if(isMenuOpen) {
      const mobileNav = document.createElement('div');
      mobileNav.className = 'md:hidden fixed inset-0 bg-white z-50';
      mobileNav.innerHTML = `
        <div class="flex justify-end p-6">
          <button class="w-10 h-10 flex items-center justify-center" id="closeMenu">
            <i class="ri-close-line text-xl"></i>
          </button>
        </div>
        <div class="flex flex-col items-center space-y-6 p-6">
          ${nav.innerHTML}
          ${getStartedButton.outerHTML}
        </div>
      `;
      
      document.body.appendChild(mobileNav);
      document.body.style.overflow = 'hidden';
      
      document.getElementById('closeMenu').addEventListener('click', toggleMenu);
      
      mobileNav.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', toggleMenu);
      });
    } else {
      const mobileNav = document.querySelector('.md\\:hidden.fixed');
      if(mobileNav) {
        mobileNav.remove();
        document.body.style.overflow = '';
      }
    }
  }
  
  mobileMenuButton.addEventListener('click', toggleMenu);
});
</script>
</body>
</html>