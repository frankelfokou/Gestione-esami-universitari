export class ExamView {
    constructor() {
        this.tableBody = document.getElementById('examTableBody');
        this.mediaAritmeticaLabel = document.getElementById('mediaAritmeticaVal');
        this.mediaPonderataLabel = document.getElementById('mediaPonderataVal');
        this.proiezioneLabel = document.getElementById('proiezioneVal');
        this.btnAdd = document.getElementById('btnAddExam');
    }

    // Metodo chiamato dal Presenter per disegnare la tabella
    renderTable(esami) {
        this.tableBody.innerHTML = '';
        
        esami.forEach(esame => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${esame.materia}</td>
                <td>${esame.voto}${esame.lode ? 'L' : ''}</td>
                <td>${esame.data}</td>
                <td>${esame.cfu}</td>
                <td>
                <button class="btn-delete" data-id="${esame.id}">Elimina Esame</button>
                </td>
            `;
            this.tableBody.appendChild(row);
        });
    }

    // Metodo per aggiornare i box viola a destra
    updateStats(mediaA, mediaP, proiezione) {
        this.mediaAritmeticaLabel.innerText = mediaA.toFixed(2);
        this.mediaPonderataLabel.innerText = mediaP.toFixed(2);
        this.proiezioneLabel.innerText = Math.round(proiezione);
    }

    bindAddExam(handler) {
        this.btnAdd.addEventListener('click', () => {
            const materia = prompt("Inserisci materia:");
            const voto = prompt("Inserisci voto:");
            if(materia && voto) {
                handler({ materia, voto: parseInt(voto), cfu: 6, data: '2024-01-01' });
            }
        });
    }

    bindDeleteExam(handler) {
    this.tableBody.addEventListener('click', (event) => {
        if (event.target.classList.contains('btn-delete')) {
            const id = parseInt(event.target.dataset.id);
            handler(id);
        }
    });
}
}