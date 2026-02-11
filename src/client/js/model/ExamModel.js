export class ExamModel {
    constructor() {
        // DATI MOCK (Simuliamo il database)
        this.esami = [
            { id: 1, materia: "Analisi Matematica 1", voto: 28, lode: false, cfu: 9, data: "2023-02-15" },
            { id: 2, materia: "Fisica", voto: 24, lode: false, cfu: 6, data: "2023-06-20" },
            { id: 3, materia: "Programmazione", voto: 30, lode: true, cfu: 12, data: "2023-09-10" }
        ];
    }

    /**
     * Restituisce tutti gli esami
     * (In futuro qui ci sarà la fetch GET /api/esami)
     */
    getAll() {
        // Simuliamo un comportamento asincrono con una Promise (opzionale, ma realistico)
        return new Promise((resolve) => {
            setTimeout(() => resolve([...this.esami]), 100); // 100ms di ritardo finto
        });
    }

    /**
     * Aggiunge un nuovo esame
     * (In futuro qui ci sarà la fetch POST /api/esami)
     */
    add(esame) {
        return new Promise((resolve) => {
            // Assegna un ID finto progressivo
            const newId = this.esami.length > 0 ? Math.max(...this.esami.map(e => e.id)) + 1 : 1;

            const nuovoEsameConId = { ...esame, id: newId };
            this.esami.push(nuovoEsameConId);

            console.log("💾 [MOCK DB] Esame salvato:", nuovoEsameConId);
            resolve({ success: true, id: newId });
        });
    }

    /**
     * Rimuove un esame per ID
     */
    remove(id) {
        return new Promise((resolve) => {
            this.esami = this.esami.filter(e => e.id !== id);
            console.log("[MOCK DB] Esame ${id} eliminato");
            resolve({ success: true });
        });
    }

    /**
     * Calcola le statistiche (Business Logic)
     */
    async getStats() {
        try {
            const response = await fetch('/api/stats');
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return await response.json();
        } catch (error) {
            console.error('Error fetching stats:', error);
            return { mediaA: 0, mediaP: 0, proiezione: 0, cfuTotali: 0 };
        }
    }
}