document.addEventListener('DOMContentLoaded', function() {
    const allArticles = Array.from(document.querySelectorAll('#articles-container article'));
        // Variables for pagination
        const articlesPerPage = 3;
        let currentPage = 1;

        // Function to render articles
        function renderArticles() {
            const container = document.getElementById('articles-container');
            if(container){
                container.innerHTML = ''; // Clear the container
            }
           

            const start = (currentPage - 1) * articlesPerPage;
            const end = start + articlesPerPage;
            const currentArticles = allArticles.slice(start, end);

            currentArticles.forEach(article => {
                container.appendChild(article);
            });
        }

        // Function to render pagination buttons
        function renderPagination() {
            const container = document.getElementById('pagination-container');
            container.innerHTML = '';
            const totalPages = Math.ceil(allArticles.length / articlesPerPage);

            const prevButton = document.createElement('button');
            prevButton.textContent = '<';
            prevButton.disabled = currentPage === 1;
            prevButton.className = currentPage === 1 ? 'disabled' : '';
            prevButton.onclick = () => {
                currentPage--;
                updateUI();
            };
            container.appendChild(prevButton);

            for (let i = 1; i <= totalPages; i++) {
                const pageButton = document.createElement('button');
                pageButton.textContent = i;
                pageButton.className = currentPage === i ? 'disabled' : '';
                pageButton.onclick = () => {
                    currentPage = i;
                    updateUI();
                };
                container.appendChild(pageButton);
            }

            const nextButton = document.createElement('button');
            nextButton.textContent = '>';
            nextButton.disabled = currentPage === totalPages;
            nextButton.className = currentPage === totalPages ? 'disabled' : '';
            nextButton.onclick = () => {
                currentPage++;
                updateUI();
            };
            container.appendChild(nextButton);
        }

        // Function to update the UI
        function updateUI() {
            renderArticles();
            renderPagination();
        }

        // Initial rendering
        updateUI();

});