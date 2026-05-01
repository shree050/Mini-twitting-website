document.addEventListener("DOMContentLoaded", () => {

  /* =========================
     🔍 LIVE USER SEARCH
  ========================= */
  const searchInput = document.getElementById("userSearch");
  const resultsBox = document.getElementById("searchResults");

  if (searchInput && resultsBox) {
    let firstResultLink = null;

    searchInput.addEventListener("input", () => {
      const q = searchInput.value.trim();

      if (q.length === 0) {
        resultsBox.innerHTML = "";
        resultsBox.style.display = "none";
        firstResultLink = null;
        return;
      }

      fetch("actions/search_user.php?q=" + encodeURIComponent(q))
        .then(res => res.text())
        .then(html => {

          if (!html.trim()) {
            resultsBox.innerHTML = "<div style='padding:10px;color:#555;'>No users found</div>";
            firstResultLink = null;
          } else {
            resultsBox.innerHTML = html;

            const first = resultsBox.querySelector(".search-item");
            firstResultLink = first ? first.getAttribute("href") : null;
          }

          resultsBox.style.display = "block";
        })
        .catch(() => {
          resultsBox.innerHTML = "";
          resultsBox.style.display = "none";
        });
    });

    searchInput.addEventListener("keydown", e => {
      if (e.key === "Enter") {
        e.preventDefault();
        if (firstResultLink) {
          window.location.href = firstResultLink;
        }
      }
    });

    document.addEventListener("click", (e) => {
      if (!e.target.closest("#searchResults") && !e.target.closest("#userSearch")) {
        resultsBox.innerHTML = "";
        resultsBox.style.display = "none";
      }
    });

    searchInput.addEventListener("click", e => e.stopPropagation());
    resultsBox.addEventListener("click", e => e.stopPropagation());
  }


  /* =========================
     ❤️ LIKE FUNCTION FIX
  ========================= */

  window.likeTweet = function(id) {

  fetch("actions/like.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded"
    },
    body: "tweet_id=" + id
  })
  .then(res => res.json()) // ✅ FIX
  .then(data => {

    // ✅ update count properly
    document.getElementById("like-count-" + id).innerText = data.count;

    // ✅ update icon based on backend
    const icon = document.getElementById("like-icon-" + id);

    if (data.liked) {
      icon.innerText = "❤️";
      icon.classList.add("liked");
    } else {
      icon.innerText = "🤍";
      icon.classList.remove("liked");
    }

  })
  .catch(() => console.log("Like failed"));
};


  /* =========================
     ✏️ TWEET CHARACTER COUNTER
  ========================= */

  const tweetBox = document.querySelector("textarea[name='content']");
  if (tweetBox) {

    const counter = document.createElement("div");
    counter.style.textAlign = "right";
    counter.style.fontSize = "12px";
    counter.style.color = "#657786";
    counter.innerText = "280";

    tweetBox.parentNode.appendChild(counter);

    tweetBox.addEventListener("input", () => {
      const remaining = 280 - tweetBox.value.length;
      counter.innerText = remaining;

      if (remaining < 20) counter.style.color = "red";
      else counter.style.color = "#657786";
    });

  }


  /* =========================
     📷 IMAGE PREVIEW
  ========================= */

  const imageInput = document.querySelector("input[name='tweet_image']");

  if (imageInput) {

    const preview = document.createElement("img");
    preview.style.maxWidth = "100%";
    preview.style.marginTop = "10px";
    preview.style.borderRadius = "10px";
    preview.style.display = "none";

    imageInput.parentNode.appendChild(preview);

    imageInput.addEventListener("change", () => {

      const file = imageInput.files[0];
      if (!file) return;

      const reader = new FileReader();

      reader.onload = e => {
        preview.src = e.target.result;
        preview.style.display = "block";
      };

      reader.readAsDataURL(file);

    });

  }

});