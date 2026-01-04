import pandas as pd
import os

# Rutas relativas (más compatible entre Windows/Mac)
INPUT_FILE = os.path.join('data', 'daily_food_nutrition_dataset.csv')
OUTPUT_FILE = os.path.join('data', 'nutrition_cleaned.csv')

def limpiar_dataset():
    print(f"--- Iniciando limpieza de datos ---")
    
    if not os.path.exists(INPUT_FILE):
        print(f"ERROR: No se encuentra el archivo en {INPUT_FILE}")
        return

    try:
        # CORRECCIÓN AQUÍ: Agregamos on_bad_lines='skip' para ignorar filas rotas
        # y engine='python' que es más tolerante a errores
        df = pd.read_csv(INPUT_FILE, on_bad_lines='skip', engine='python')
        print(f"Datos originales cargados: {len(df)} registros.")
    except Exception as e:
        print(f"Error fatal al leer CSV: {e}")
        return

    # Renombrar columnas (Verifica los nombres exactos en tu CSV si esto falla)
    # A veces el CSV trae espacios extra tipo " Calories"
    df.columns = df.columns.str.strip() # Limpia espacios en nombres de columnas
    
    nuevas_columnas = {
        'Food_Item': 'Nombre',
        'Category': 'Categoria',
        'Calories (kcal)': 'Calorias',
        'Protein (g)': 'Proteinas',
        'Carbohydrates (g)': 'Carbohidratos',
        'Fats (g)': 'Grasas'
    }
    
    # Filtramos solo las que existen para evitar otro error
    cols_existentes = [c for c in nuevas_columnas.keys() if c in df.columns]
    
    if not cols_existentes:
        print("ALERTA: No se encontraron las columnas esperadas.")
        print("Columnas en el archivo:", df.columns.tolist())
        return

    df = df[cols_existentes].rename(columns=nuevas_columnas)

    # Eliminar duplicados y limpiar
    df_unico = df.drop_duplicates(subset=['Nombre'])
    
    # Asegurar que sean números
    cols_numericas = ['Calorias', 'Proteinas', 'Carbohidratos', 'Grasas']
    for col in cols_numericas:
        if col in df_unico.columns:
            df_unico[col] = pd.to_numeric(df_unico[col], errors='coerce')
    
    df_unico = df_unico.dropna()
    
    if 'Calorias' in df_unico.columns:
        df_unico = df_unico[df_unico['Calorias'] > 0]

    # Guardar
    df_unico.to_csv(OUTPUT_FILE, index=False)
    
    print(f"--- ÉXITO ---")
    print(f"Archivo guardado en: {OUTPUT_FILE}")
    print(f"Total alimentos únicos: {len(df_unico)}")

if __name__ == "__main__":
    limpiar_dataset()
