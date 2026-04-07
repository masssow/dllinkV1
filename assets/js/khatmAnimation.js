// annimation copie lien khatm public
function copySessionLink(url) {
    navigator.clipboard.writeText(url).then(() => {
        const el = document.getElementById('copy-feedback');
        el.classList.remove('d-none');

        setTimeout(() => {
            el.classList.add('d-none');
        }, 2000);
    });
}
// Animation / état des cards session page

document.addEventListener('DOMContentLoaded', () => {
    const khatmConfig = window.khatmConfig ?? null;

    // Si on n'est pas sur une page khatm publique, on ne fait rien
    if (!khatmConfig || !khatmConfig.sessionSlug || !khatmConfig.sessionStateUrl) {
        return;
    }

    const slug = khatmConfig.sessionSlug;
    const stateUrl = khatmConfig.sessionStateUrl;

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
        let toggleUrl = card.dataset.toggleUrl;

        if (!toggleUrl) {
            console.error("toggleUrl introuvable sur la carte :", card);
            toggleLock = false;
            return;
        }

        fetch(toggleUrl, {
            method: "POST",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                "Accept": "application/json",
            }
        })
            .then(async (r) => {
                const contentType = r.headers.get("content-type") || "";

                if (!contentType.includes("application/json")) {
                    const text = await r.text();
                    console.error("Réponse non JSON reçue :", text);
                    throw new Error("Le serveur n'a pas renvoyé du JSON.");
                }

                return r.json();
            })
            .then(data => {
                card.classList.remove(
                    "hizb-free",
                    "hizb-assigned",
                    "hizb-completed",
                    "bg-white",
                    "bg-warning-subtle",
                    "border-warning",
                    "bg-success-subtle",
                    "border-success"
                );

                let oldBadge = card.querySelector(".hizb-status-badge");
                if (oldBadge) oldBadge.remove();

                if (data.completed) {
                    card.classList.add("hizb-completed", "bg-success-subtle", "border-success");
                    card.insertAdjacentHTML(
                        "beforeend",
                        "<span class='badge bg-success hizb-status-badge mt-2'>Terminé</span>"
                    );
                } else {
                    card.classList.add("hizb-assigned", "bg-warning-subtle", "border-warning");
                    card.insertAdjacentHTML(
                        "beforeend",
                        "<span class='badge bg-warning text-dark hizb-status-badge mt-2'>Terminer ce Hizb</span>"
                    );
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

        const bar = document.getElementById("khatm-progress-bar");

        if (bar) {
            bar.style.width = percent + "%";
            bar.innerText = percent + "%";
        }

        let progressText = document.getElementById("khatm-progress-text");

        if (progressText) {
            progressText.innerText = data.completedCount + " / " + data.total + " hizb complétés";
        }
    }
});