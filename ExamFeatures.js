export class ExamModel{
	constructor()
	this.apiBase='/api/esami';
	
	//Toke temporaneo o recuperato dal localStorage come da specifiche Auth
	this.token= localStorage.getItem('jwt_token');
}

//Recupera la lista esami (GET/api/esami)
async getAll(){
	try{
		/*da sostituire con fetch reale quando il backend sara pronto:
	const response = await fetch(this.apiBase,{ headers:{'Authorization': Bearer $(this.token} } });
	return await response.json();
	
	MOCK DATA (per ora)
	return[
	{ id: 1, 
	materia: "Analisi Matematica 1",
	voto: 28,
	lode: false,
	cfu: 9,
	data: "2023-02-15" },
	{ id: 2,
	materia: "Fisica",
	voto: 24,
	lode: false,
	cfu: 6,
	data: "2023-06-20" }, 
	{ id: 3,
	materia: "Programmazione",
	voto: 30,
	lode: true,
	cfu: 12,
	data: "2023-09-10" } ];
	*/
	} catch (error) { console.error("Errore nel fetch esami:", error); return []; }
			 
}

// Aggiunge un esame (POST /api/esami)
async add(esame) { 
	try {
		/* 	const response = await fetch(this.apiBase, { 
			method: 'POST', 
			headers: { 
			'Content-Type': 'application/json', 
			'Authorization': Bearer ${this.token} 
			}, 
			body: JSON.stringify(esame) 
			}); 
			return await response.json();
			*/
			
		// MOCK RESPONSE
			console.log("Mock API: Aggiungo esame", esame);
			return { success: true, id: Math.floor(Math.random() * 1000) };
	} catch (error) {
		console.error("Errore aggiunta esame:", error);
		return { success: false };
	}
}

// Calcola statistiche locali (se il backend non è pronto, altrimenti fai una chiamata GET /api/statistiche)
calculateStats(esami) {
	if (esami.length === 0) return { mediaA: 0, mediaP: 0, proiezione: 0 
	};
	
	const sommaVoti = esami.reduce((acc, e) => acc + e.voto, 0);
	onst sommaPonderata = esami.reduce((acc, e) => acc + (e.voto * e.cfu), 0);
	const totCFU = esami.reduce((acc, e) => acc + e.cfu, 0);
	
	return {
		mediaA: sommaVoti / esami.length,
		mediaP: totCFU > 0 ? sommaPonderata / totCFU : 0,
	proiezione: totCFU > 0 ? (sommaPonderata / totCFU) * 110 / 30 : 0 };
}

//Modifica un esame (PUT /esami/{id})
async UpdateEsame(id, esame){
	try{
		/*
		const response = await fetch(this.apiBase, {
		method 'PUT',
		headers : {
		'Content-Type': 'application/json', 
		'Authorization': Bearer ${this.token} 
		}, 	
		body: JSON.stringify(esame) 
		}); 
		return await response.json();
		*/
		
		//MOCK
		console.log("Mock modifica esame:", id, esame);
        return { success: true };
	
	}catch(error){
		console.error("Errore nella modifica esame:", error);
		return { success: false };
	}
	
	
	//Elimina un esame ( DELETE /esami/{id})
async DeleteEsame(id){
	try{
		/*
		const response = await fetch(this.apiBase, {
		method 'DELETE',
		headers : {
		'Content-Type': 'application/json', 
		'Authorization': Bearer ${this.token} 
		}, 	
		body: JSON.stringify(esame) 
		}); 
		return await response.json();
		*/
		
		//MOCK
		console.log("Mock elimina esame:", id);
        return { success: true };
	
	}catch(error){
		console.error("Errore nella eliminazione esame:", error);
		return { success: false };
	}