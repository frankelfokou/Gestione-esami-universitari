// 1. Importa i tre componenti fondamentali
import { ExamModel } from './model/ExamModel.js';
import { ExamView } from './view/ExamView.js';
import { ExamPresenter } from './presenter/ExamPresenter.js';

document.addEventListener('DOMContentLoaded', () => {
    console.log(" Avvio applicazione Esami...");

    // 2. Crea il Model (che contiene i dati e la logica di business)
    // Nota: I dati mockati sono ora nascosti qui dentro
    const model = new ExamModel();

    // 3. Crea la View (che gestisce l'HTML)
    //
    const view = new ExamView();

    // 4. Crea il Presenter e collegali insieme
    // Il presenter farà partire tutto grazie al suo metodo init() che abbiamo appena corretto
    const app = new ExamPresenter(view, model);
});