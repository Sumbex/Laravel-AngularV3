import { Component, OnInit } from '@angular/core';
import { SociosService } from 'src/app/servicios/socios.service';
import { ValidarUsuarioService } from 'src/app/servicios/validar-usuario.service';
import { NgbModalConfig, NgbModal, ModalDismissReasons } from '@ng-bootstrap/ng-bootstrap';
import { SindicalService } from '../../../servicios/sindical.service';
import { AniosService } from 'src/app/servicios/anios.service';
import { ConsorcioService } from 'src/app/servicios/consorcio.service';

@Component({
  selector: 'app-formulario-fondo-mutuo',
  templateUrl: './formulario-fondo-mutuo.component.html',
  styleUrls: ['./formulario-fondo-mutuo.component.css']
})
export class FormularioFondoMutuoComponent implements OnInit {


  socios;
  search: string = '';
  mod_editar = null;
  mod_validar = null;
  blockLoad = false;
  blockLoad2 = false;

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
  estado_socio: object = ['estado'];
  ver_load: boolean = true;
  ver_estado_soc: boolean = false;

   //variables select año y mes
   anio; anios;
   mes; meses;
   idAnioActual;
   idMesActual;
   suc_res1 = false;
   suc_res2 = false;

  constructor(private _socios: SociosService,
    private _time: AniosService,
    public _validarusuario: ValidarUsuarioService,
    private _consorcioService: ConsorcioService,
    private _sindical: SindicalService
  ) {

  }

  ngOnInit() {

    if (localStorage.getItem('token') == '') {
      alert("La sesión ya expiro!");

      location.reload();
    }

    this.listar();
    this.llenar_select();
  }

  listar() {
    this._consorcioService.listar_consorcio().subscribe(
      response => {
        console.log(response);
        this.socios = response;
        this.blockLoad2 = false;

      }
    )
  }

  // filtrar() {
    
  //   this.blockLoad = true;
  //   if (this.search == '') {
  //     alert("Ingrese un nombre para filtrar");
  //     this.blockLoad = false;
  //     return false;
  //   } else {
  //     this._socios.getTablaFilter(this.search).subscribe(
  //       response => {
  //         console.log(response);
  //         this.socios = response;
  //         this.blockLoad = false;

  //       }
  //     )
  //   }
  // }

  // fin del metodo para validar usuario
  private getDismissReason(reason: any): string {
    if (reason === ModalDismissReasons.ESC) {
      return 'by pressing ESC';
    } else if (reason === ModalDismissReasons.BACKDROP_CLICK) {
      return 'by clicking on a backdrop';
    } else {
      return `with: ${reason}`;
    }
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

    }
  }

  btn_reload() {

    this.listo_para_listar(this.suc_res1, this.suc_res2);

  }


}
