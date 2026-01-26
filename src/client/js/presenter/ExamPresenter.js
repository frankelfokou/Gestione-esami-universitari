export class ExamPresenter {
    constructor(view, model) {
        this.view = view;
        this.model = model;
        
        
        this.init();
    }
    
    async init() {
        this.view.bindAddExam(this.handleAddExam.bind(this));

        this.view.bindDeleteExam(this.handleDeleteExam.bind(this));
        
        
        await this.refresh();
    }
     
    // Ricarica tabella E statistiche
    async refresh() {
        
        const esami = await this.model.getAll(); 
        const stats = this.model.getStats(); 

        
        this.view.renderTable(esami);

        
        this.view.updateStats(
            stats.mediaA,
            stats.mediaP,
            stats.proiezione
        );
    }
    
    async handleAddExam(esame) {
        await this.model.add(esame);
        await this.refresh();
    }

    async handleDeleteExam(id) {

    await this.model.remove(id);
    await this.refresh();
    }
}