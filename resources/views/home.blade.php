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
<a href="{{ url('/') }}" class="text-3xl font-['Pacifico'] text-primary">E mebel</a>
<nav class="hidden md:flex items-center space-x-8">
<a href="{{ url('/') }}" class="nav-link text-gray-800 hover:text-primary">Home</a>
<a href="#about" class="nav-link text-gray-800 hover:text-primary transition-all">About</a> 

<a href="{{ url('/contact') }}" class="nav-link text-gray-800 hover:text-primary">Contact</a>
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
<section id="about" class="w-full px-6 md:px-12 py-16 bg-gray-50">
  <div class="text-center mb-16">
    <h2 class="text-3xl md:text-4xl font-bold mb-4">About me</h2>
    <p class="text-gray-600 max-w-2xl mx-auto">proyek ini untuk sebagai memenuhi tugas project yang telah kampus berikan kepada kami</p>
  </div>
  
  <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-md transition-all">
      <div class="w-12 h-12 flex items-center justify-center bg-primary/10 rounded-xl mb-6">
        <i class="ri-palette-line text-2xl text-primary"></i>
      </div>
      <h3 class="text-xl font-semibold mb-3">Designer enginer</h3>
      <p class="text-gray-600">Putri Ayu Fadhillah 2305022</p>
    </div>
    
    <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-md transition-all">
      <div class="w-12 h-12 flex items-center justify-center bg-primary/10 rounded-xl mb-6">
        <i class="ri-code-box-line text-2xl text-primary"></i>
      </div>
      <h3 class="text-xl font-semibold mb-3">management Developer</h3>
      <p class="text-gray-600">Khoerul Paroid 2305013
      </p>
    </div>
    
    <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-md transition-all">
      <div class="w-12 h-12 flex items-center justify-center bg-primary/10 rounded-xl mb-6">
        <i class="ri-customer-service-line text-2xl text-primary"></i>
      </div>
      <h3 class="text-xl font-semibold mb-3">dokumenter developer</h3>
      <p class="text-gray-600">Hamzah Fauji Pratama 2305011</p>
    </div>
  </div>
</section>
<section class="w-full px-6 md:px-12 py-16">
  <div class="text-center mb-16">
    <h3 class="text-3xl md:text-4xl font-bold mb-4">mau tau kami?</h3>
    <p class="text-gray-600 max-w-2xl mx-auto">nih yang mau kenal sama kitaa</p>
  </div>
  
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
    <div class="group relative overflow-hidden rounded-2xl">
      <img src="{{ asset('images/KhoerulParoid.jpg') }}" alt="Khoerul Paroid" class="w-full h-64 object-cover">
      <div class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
        <div class="text-center text-white p-6">
          <h3 class="text-xl font-semibold mb-2">Khoerul Paroid</h3>
          <p class="text-sm">Developer</p>
        </div>
      </div>
    </div>
  
    <div class="group relative overflow-hidden rounded-2xl">
      <img src="{{ asset('images/PutriAyuFadhilah.jpg') }}" alt="Putri Ayu Fadhillah" class="w-full h-64 object-cover">
      <div class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
        <div class="text-center text-white p-6">
          <h3 class="text-xl font-semibold mb-2">Putri Ayu Fadhillah</h3>
          <p class="text-sm">Spesialis Designer</p>
        </div>
      </div>
    </div>
  
    <div class="group relative overflow-hidden rounded-2xl">
      <img src="{{ asset('images/HamzahFaujiPratama.jpg') }}" alt="Hamzah Fauji Pratama" class="w-full h-64 object-cover">
      <div class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
        <div class="text-center text-white p-6">
          <h3 class="text-xl font-semibold mb-2">Hamzah Fauji Pratama</h3>
          <p class="text-sm">Dokumenter Developer</p>
          <p class="text-sm">AFK</p>
        </div>
      </div>
    </div>
  </div>
  
</section>
<section class="w-full px-6 md:px-12 py-16 bg-primary/5">
  <div class="max-w-4xl mx-auto">
    <div class="text-center mb-12">
      <h2 class="text-3xl md:text-4xl font-bold mb-4">Tinggalkan pesan untuk kami</h2>
      <p class="text-gray-600">
        punya pesan ataupun kesan? suarakan melalui pesan ke kami, suara kalian inspirasi bagi kami <br>
        atau punya keluhan tentang sistem, service, dan lain lain. Boleh bagikan ke kami jugaa <br>
        kami tunggu suara anda.......
      </p>
    </div>
    <div class="bg-white p-8 rounded-2xl shadow-lg">
      <form
        id="contactForm"
        class="grid grid-cols-1 md:grid-cols-2 gap-6"
      >
        <div class="md:col-span-1">
          <label
            class="block text-sm font-medium text-gray-700 mb-2"
            for="name"
            >Full Name</label
          >
          <input
            type="text"
            id="name"
            name="name"
            class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-primary focus:border-primary text-sm"
            placeholder="Salman jeh"
            required
          />
        </div>
        <div class="md:col-span-1">
          <label
            class="block text-sm font-medium text-gray-700 mb-2"
            for="email"
            >Email Address</label
          >
          <input
            type="email"
            id="email"
            name="email"
            class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-primary focus:border-primary text-sm"
            placeholder="anu@gmail.com"
            required
          />
        </div>
        <div class="md:col-span-2">
          <label
            class="block text-sm font-medium text-gray-700 mb-2"
            for="message"
            >Message</label
          >
          <textarea
            id="message"
            name="message"
            rows="6"
            class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-primary focus:border-primary text-sm"
            placeholder="Tell us about your project..."
            required
          ></textarea>
        </div>
        <div class="md:col-span-2">
          <button
            type="submit"
            class="w-full bg-primary text-white py-3 px-8 !rounded-button whitespace-nowrap hover:bg-opacity-90 transition-all"
          >
            Send Message
          </button>
        </div>
      </form>
    </div>
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
<footer class="w-full bg-primary/5 text-gray-800 px-6 md:px-12 pt-16 pb-8">
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
            href="{{ url('/about') }}"
            class="text-gray-600 hover:text-primary transition-colors"
            >About</a
          >
        </li>
        <li>
          <a
            href="{{ url('/contact') }}"
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
          <i class="ri-map-pin-line"></i>
          <span class="text-gray-600">departement mebel, electronic mebel</span>
        </li>
        <li class="flex items-center gap-2">
          <i class="ri-mail-line"></i>
          <a
            href="mailto:hello@example.com"
            class="text-gray-600 hover:text-primary transition-colors"
            >Emebel@gmail.com</a
          >
        </li>
        <li class="flex items-center gap-2">
          <i class="ri-phone-line"></i>
          <a
            href="tel:+1234567890"
            class="text-gray-600 hover:text-primary transition-colors"
            >+62 858-567-890</a
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