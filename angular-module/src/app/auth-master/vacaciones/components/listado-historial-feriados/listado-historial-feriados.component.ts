import { Component, OnInit } from "@angular/core";

@Component({
  selector: "app-listado-historial-feriados",
  templateUrl: "./listado-historial-feriados.component.html",
  styleUrls: ["./listado-historial-feriados.component.css"],
})
export class ListadoHistorialFeriadosComponent implements OnInit {
  feriados : Object[] = [
    {
      solicitud: "19/01/2022",
      inicio: "21/01/2022",
      termino: "08/01/2022",
      d_legales: "17",
      d_progresivos: "0",
      doc: "documento.pdf",
    },
    {
      solicitud: "16/01/2022",
      inicio: "21/01/2022",
      termino: "08/01/2022",
      d_legales: "17",
      d_progresivos: "0",
      doc: "documento.pdf",
    },

    {
      solicitud: "19/01/2022",
      inicio: "21/01/2022",
      termino: "08/01/2022",
      d_legales: "17",
      d_progresivos: "0",
      doc: "documento.pdf",
    },
    {
      solicitud: "19/01/2022",
      inicio: "21/01/2022",
      termino: "08/01/2022",
      d_legales: "17",
      d_progresivos: "0",
      doc: "documento.pdf",
    },
    {
      solicitud: "19/01/2022",
      inicio: "21/01/2022",
      termino: "08/01/2022",
      d_legales: "17",
      d_progresivos: "0",
      doc: "documento.pdf",
    },
    {
      solicitud: "19/01/2022",
      inicio: "21/01/2022",
      termino: "08/01/2022",
      d_legales: "17",
      d_progresivos: "0",
      doc: "documento.pdf",
    },
    {
      solicitud: "19/01/2022",
      inicio: "21/01/2022",
      termino: "08/01/2022",
      d_legales: "17",
      d_progresivos: "0",
      doc: "documento.pdf",
    },
    {
      solicitud: "19/01/2022",
      inicio: "21/01/2022",
      termino: "08/01/2022",
      d_legales: "17",
      d_progresivos: "0",
      doc: "documento.pdf",
    },
    {
      solicitud: "19/01/2022",
      inicio: "21/01/2022",
      termino: "08/01/2022",
      d_legales: "17",
      d_progresivos: "0",
      doc: "documento.pdf",
    },
    {
      solicitud: "19/01/2022",
      inicio: "21/01/2022",
      termino: "08/01/2022",
      d_legales: "17",
      d_progresivos: "0",
      doc: "documento.pdf",
    },
  ];
  constructor() {}

  ngOnInit() {}
}
