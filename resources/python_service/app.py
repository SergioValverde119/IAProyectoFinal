from flask import Flask, request, jsonify
from genetic_algorithm import AgenteNutricional

app = Flask(__name__)
agente = AgenteNutricional()

@app.route('/generar-plan-completo', methods=['POST'])
def generar_plan_completo():
    datos = request.get_json()
    
    # Validar campos básicos
    campos_requeridos = ['edad', 'genero', 'peso', 'altura', 'objetivo']
    if not all(k in datos for k in campos_requeridos):
        return jsonify({"error": "Faltan datos del perfil"}), 400
        
    # Ejecutar lógica
    resultado = agente.generar_plan_personalizado(datos)
    
    return jsonify(resultado)

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)
