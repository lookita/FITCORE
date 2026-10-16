interface Nino {
  nombre: string;
  edad: number;
}


// Partial hace que ni el nombre ni la edad sean obligatorios
export const ninoIncompleto: Partial<Nino> = {
  nombre: "Mateo" // No dio la edad y TypeScript no genera error
};
