import { ExamView } from './view/ExamView.js';

document.addEventListener('DOMContentLoaded', () => {
    const view = new ExamView();

    // mock dati
    const esamiFinti = [
        { materia: "Analisi Matematica 1", voto: 28, lode: false, cfu: 9, data: "2023-02-15" },
        { materia: "Fisica", voto: 24, lode: false, cfu: 6, data: "2023-06-20" },
        { materia: "Programmazione", voto: 30, lode: true, cfu: 12, data: "2023-09-10" }
    ];

    // Disegna la tabella
    view.renderTable(esamiFinti);

    // Aggiorna le statistiche (mock)
    view.updateStats(27.3, 28.1, 105);

    // 3. Attiva il bottone "+"
    view.bindAddExam((nuovoEsame) => {
        console.log("Hai provato ad aggiungere:", nuovoEsame);
        esamiFinti.push(nuovoEsame); // Aggiunge alla lista locale
        view.renderTable(esamiFinti); // Ridisegna la tabella
        alert("Esame aggiunto graficamente! (Non salvato nel DB)");
    });
});