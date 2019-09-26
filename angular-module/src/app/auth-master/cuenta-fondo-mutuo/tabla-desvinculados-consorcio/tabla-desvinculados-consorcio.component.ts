import { Component, OnInit } from '@angular/core';
import { AniosService } from 'src/app/servicios/anios.service';
import { ConsorcioService } from 'src/app/servicios/consorcio.service';

@Component({
  selector: 'app-tabla-desvinculados-consorcio',
  templateUrl: './tabla-desvinculados-consorcio.component.html',
  styleUrls: ['./tabla-desvinculados-consorcio.component.css']
})
export class TablaDesvinculadosConsorcioComponent implements OnInit {

  socios;
  sociosMensual;
  sociosAnual;
  search: string = '';
  mod_editar = null;
  mod_validar = null;
  blockLoad = false;
  blockLoad2 = false;
  contador = 3;

  //actualizar_socio
  rut;
  nombres;
  a_paterno;
  a_materno;
  fecha_nacimiento;
  fecha_ingreso;
  fecha_egreso;

  //para validar usuario
  user: object = [];
  load: boolean = false;
  modalReference = null;
  m_val = null;
  mod_opcion = null;

  closeResult: string;
  pass: string = '';
  btn_validar: boolean = false;

  currentLesson: string;

  buttonStatus = false;
  token = localStorage.getItem('token').replace(/['"]+/g, '');
  // estado_socio: object = ['estado'];
  ver_load: boolean = true;
  ver_estado_soc: boolean = false;

  //variables select año y mes
  anio; anios;
  mes; meses;
  idAnioActual;
  idMesActual;
  suc_res1 = false;
  suc_res2 = false;
  //ingresar consorcio
  blockIngreso: boolean = false;

  constructor(
    private _time: AniosService,
    private _consorcioService: ConsorcioService
  ) {

  }

  ngOnInit() {

    if (localStorage.getItem('token') == '') {
      alert("La sesión ya expiro!");

      location.reload();
    }

    this.llenar_select();
  }

  listar() {
    this.blockLoad2 = true;
    this._consorcioService.getTablaConsorcios(this.anio).subscribe(
      response => {
        console.log(response);
        this.socios = response;
        this.contador--;
        if (this.contador == 0) {
          this.blockLoad2 = false;
        }

      }
    )
  }

  listarMensual() {
    // this.blockLoad2 = true;
    this._consorcioService.getTablaConsorciosTotalesMensuales(this.anio).subscribe(
      response => {
        console.log(response);
        this.sociosMensual = response;
        this.contador--;
        if (this.contador == 0) {
          this.blockLoad2 = false;
        }

      }
    )
  }
  listarAnual() {
    // this.blockLoad2 = true;
    this._consorcioService.getTablaConsorciosTotalAnual(this.anio).subscribe(
      response => {
        console.log(response);
        this.sociosAnual = response;
        this.contador--;
        if (this.contador == 0) {
          this.blockLoad2 = false;
        }

      }
    )
  }

  llenar_select() {

    this._time.getAnios().subscribe(
      response => {
        this.anios = response;


        this._time.getAnioActual().subscribe(
          (response: { id }) => {
            this.anio = response.id;
            this.suc_res1 = true;
            this.listo_para_listar(this.suc_res1, this.suc_res2);

          },
          error => {
            console.log(error);
          }
        )
      },
      error => {
        console.log(error);
      }
    )

    this._time.getMeses().subscribe(
      response => {
        this.meses = response;

        this._time.getMesActual().subscribe(
          (response: { id }) => {
            this.mes = response.id;
            this.suc_res2 = true;
            this.listo_para_listar(this.suc_res1, this.suc_res2);
          },
          error => {
            console.log(error);
          }
        )
      },
      error => {
        console.log(error);
      }
    )
  }

  listo_para_listar(res1, res2) {
    if (res1 == true && res2 == true) {
      this.listar();
      this.listarMensual();
      this.listarAnual();
    }
  }

  btn_reload() {
    this.contador=3;
    this.listo_para_listar(this.suc_res1, this.suc_res2);

  }

  ConvertToInt(val){
    return parseInt(val);
  }



}
