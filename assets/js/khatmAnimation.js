// Animation / état des cards session page

if (!window.khatmConfig) {
    console.log('khatmAnimation skipped: no khatmConfig found on this page.');
} else {
    const slug = window.khatmConfig.sessionSlug;
    const stateUrl = window.khatmConfig.sessionStateUrl;
    const toggleBase = window.khatmConfig.toggleUrlBase;

    let khatmPollingStarted = false;
    let toggleLock = false;

    function refreshKhatm() {
        if (toggleLock) return;

        fetch(stateUrl)
            .then(r => r.json())
            .then(data => {
                data.forEach(hizb => {
                    let card = document.getElementById("hizb-" + hizb.juz);

                    if (!card) return;

                    card.classList.remove(
                        "hizb-free",
                        "hizb-assigned",
                        "hizb-completed"
                    );

                    if (hizb.completed) {
                        card.classList.add("hizb-completed");
                    } else if (hizb.participant) {
                        card.classList.add("hizb-assigned");
                    } else {
                        card.classList.add("hizb-free");
                    }

                    let participant = card.querySelector(".participant");

                    if (participant) {
                        participant.innerText = hizb.participant ?? "Libre";
                    }
                });
            })
            .catch(error => {
                console.error('Erreur refreshKhatm:', error);
            });
    }

    function startPolling() {
        if (khatmPollingStarted) return;

        khatmPollingStarted = true;
        setInterval(refreshKhatm, 5000);
    }

    startPolling();

    window.toggleHizb = function (card) {
        if (toggleLock) return;

        toggleLock = true;

        let id = card.dataset.id;
        let juz = card.dataset.juz;

        fetch(toggleBase + id, {
            method: "POST"
        })
            .then(r => r.json())
            .then(data => {
                card.classList.remove(
                    "hizb-assigned",
                    "hizb-completed"
                );

                let action = card.querySelector(".hizb-action");
                let done = card.querySelector(".hizb-done");

                if (action) action.remove();
                if (done) done.remove();

                if (data.completed) {
                    card.classList.add("hizb-completed");
                    card.insertAdjacentHTML("beforeend", "<span class='hizb-done'>Terminé</span>");
                } else {
                    card.classList.add("hizb-assigned");
                    card.insertAdjacentHTML("beforeend", "<span class='hizb-action'>Terminer ce Hizb</span>");
                }

                updateProgress(data);
            })
            .catch(error => {
                console.error('Erreur toggleHizb:', error);
            })
            .finally(() => {
                toggleLock = false;
            });
    };

    function updateProgress(data) {
        let percent = Math.round(
            (data.completedCount / data.total) * 100
        );

        let bar = document.querySelector(".progress-bar");

        if (bar) {
            bar.style.width = percent + "%";
            bar.innerText = percent + "%";
        }

        let progressText = document.querySelector(".khatm-progress p");

        if (progressText) {
            progressText.innerText = data.completedCount + " / " + data.total + " hizb complétés";
        }
    }
}