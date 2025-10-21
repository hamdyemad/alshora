<a href="https://wa.me/+201011559674" target="_blank" class="whatsapp-float" title="Chat on WhatsApp">
    <i class="uil uil-whatsapp"></i>
</a>

<style>
    .whatsapp-float {
        position: fixed;
        width: 60px;
        height: 60px;
        bottom: 30px;
        right: 30px;
        background-color: #25d366;
        color: #FFF;
        border-radius: 50px;
        text-align: center;
        font-size: 30px;
        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .whatsapp-float:hover {
        background-color: #128c7e;
        transform: scale(1.1);
        box-shadow: 2px 2px 15px rgba(0, 0, 0, 0.4);
        color: #FFF;
    }

    .whatsapp-float i {
        line-height: 60px;
    }

    /* RTL Support */
    [dir="rtl"] .whatsapp-float {
        right: auto;
        left: 30px;
    }

    /* Responsive */
    @media screen and (max-width: 768px) {
        .whatsapp-float {
            width: 50px;
            height: 50px;
            bottom: 20px;
            right: 20px;
            font-size: 25px;
        }

        .whatsapp-float i {
            line-height: 50px;
        }

        [dir="rtl"] .whatsapp-float {
            right: auto;
            left: 20px;
        }
    }
</style>
