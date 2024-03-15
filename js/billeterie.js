document.addEventListener('DOMContentLoaded', function(){
    
    
        const tooltip = document.querySelectorAll('.tooltip');
        const tribunes = document.querySelectorAll('.tribune');
        tribunes.forEach(tribune=>{
            tribune.addEventListener("mousemove", function(event) {
                tooltip.forEach(tool => {
                    tool.style.left = (event.clientX - 800) + '%'; // Décalage horizontal de 10 pixels
                    tool.style.top = (event.clientY - 520) + '%'; // Décalage vertical de 40 pixels
                });
        })
    });
});