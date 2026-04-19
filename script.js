document.addEventListener('DOMContentLoaded', () => {
    const navbar = document.getElementById('navbar');
    const menuBtn = document.getElementById('click');
    const navLinks = document.querySelectorAll('.nav-links a');
    const sections = document.querySelectorAll('section, header');

    let isScrolling = false;
    window.addEventListener('scroll', () => {
        if (!isScrolling) {
            window.requestAnimationFrame(() => {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
                
                updateActiveLink();
                isScrolling = false;
            });
            isScrolling = true;
        }
    });

    function updateActiveLink() {
        let current = "";
        sections.forEach((section) => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (window.scrollY >= sectionTop - 150) {
                current = section.getAttribute("id");
            }
        });

        navLinks.forEach((link) => {
            link.classList.remove("active");
            if (link.getAttribute("href").includes(current)) {
                link.classList.add("active");
            }
        });
    }

    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (menuBtn.checked) {
                menuBtn.checked = false;
            }
        });
    });

    const observerOptions = {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px"
    };

    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);

    document.querySelectorAll('section').forEach(section => {
        revealObserver.observe(section);
    });

    const contactItems = document.querySelectorAll('.contact-item');

    contactItems.forEach(item => {
        item.style.cursor = 'pointer'; 
        
        item.addEventListener('click', () => {
            const h4Element = item.querySelector('h4');
            if (!h4Element) return;

            const type = h4Element.innerText.toLowerCase();
            
            if (type.includes('email')) {
                window.location.href = "mailto:odditytechservice@gmail.com?subject=Inquiry from Website";
            } 
            else if (type.includes('call')) {
                window.location.href = "tel:+255774574261";
            } 
            else if (type.includes('location')) {
                window.open("https://www.google.com/maps?q=Dar+es+Salaam", "_blank");
            }
            
            item.style.transform = "scale(0.95)";
            setTimeout(() => {
                item.style.transform = ""; 
            }, 100);
        });
    });
});