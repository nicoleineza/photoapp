document.addEventListener("DOMContentLoaded", function() {
    const portfolioGrid = document.getElementById("portfolioGrid");
    const lightbox = document.getElementById("lightbox");
    const lightboxImage = document.getElementById("lightboxImage");
    const reviewForm = document.getElementById("reviewForm");
    const reviewMessage = document.getElementById("reviewMessage");
  
    portfolioGrid.addEventListener("click", function(event) {
      const target = event.target;
      if (target.classList.contains("portfolio-item")) {
        lightboxImage.src = target.querySelector("img").src;
        lightbox.style.display = "flex";
      } else if (target.classList.contains("review-button")) {
        const portfolioItem = target.closest(".portfolio-item");
        const photographerId = portfolioItem.dataset.photographerId;
        const imageId = portfolioItem.dataset.imageId;
        openReviewForm(photographerId, imageId);
      } else if (target.classList.contains("comments-button")) {
        const portfolioItem = target.closest(".portfolio-item");
        const commentsContainer = portfolioItem.querySelector(".comments-container");
        toggleCommentsVisibility(commentsContainer);
      }
    });
  
    function openReviewForm(photographerId, imageId) {
      document.getElementById("photographerId").value = photographerId;
      document.getElementById("imageId").value = imageId;
      reviewForm.style.display = "block";
    }
  
    function toggleCommentsVisibility(commentsContainer) {
      if (commentsContainer.style.display === "none" || commentsContainer.style.display === "") {
        const imageId = commentsContainer.closest(".portfolio-item").dataset.imageId;
        fetchAndDisplayComments(imageId, commentsContainer);
        commentsContainer.style.display = "block";
      } else {
        commentsContainer.style.display = "none";
      }
    }
    function toggleSidebar() {
        var sidebar = document.querySelector('.sidebar');
        sidebar.style.display = (sidebar.style.display === 'none' || sidebar.style.display === '') ? 'flex' : 'none';
      }
  
    function fetchAndDisplayComments(imageId, commentsContainer) {
      fetch(`../functions/fetch_comments.php?image_id=${imageId}`)
        .then(response => response.json())
        .then(data => {
          commentsContainer.innerHTML = "";
          if (data.length > 0) {
            data.forEach(comment => {
              const commentElement = document.createElement("p");
              commentElement.textContent = comment;
              commentsContainer.appendChild(commentElement);
            });
          } else {
            const noCommentsMessage = document.createElement("p");
            noCommentsMessage.textContent = "No comments yet.";
            commentsContainer.appendChild(noCommentsMessage);
          }
        })
        .catch(error => {
          console.error('Error fetching comments:', error);
        });
    }
  
    reviewForm.addEventListener("submit", function(event) {
      event.preventDefault();
      const formData = new FormData(this);
      fetch(this.action, {
        method: 'POST',
        body: formData
      })
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.text();
      })
      .then(data => {
        reviewMessage.textContent = data;
        reviewMessage.style.display = "block";
        reviewForm.reset();
        reviewForm.style.display = "none";
      })
      .catch(error => {
        console.error('There was a problem with your fetch operation:', error);
      });
    });
  });