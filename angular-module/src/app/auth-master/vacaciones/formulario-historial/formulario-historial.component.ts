import { Component, OnInit, Self } from '@angular/core';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { LiquidacionJuanitoService } from '../../../servicios/liquidacion-juanito.service';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { VacacionesService } from '../../../servicios/vacaciones.service';

@Component({
  selector: 'app-formulario-historial',
  templateUrl: './formulario-historial.component.html',
  styleUrls: ['./formulario-historial.component.css']
})
export class FormularioHistorialComponent implements OnInit {

  blockIngreso: boolean = false;
  empleados: any;
  ventana: any;
  form:FormGroup;
  tabla:any;
  comunicar: boolean = false;
  constructor(
    private liq:LiquidacionJuanitoService,
    private modalService2: NgbModal,
    private fb:FormBuilder,
    private _vacaciones:VacacionesService
    ) { }

  ngOnInit() {
    this.traer_empleados();
    this.crear_form();
    this.listar_historial();
  }

  crear_form(){
    this.form = this.fb.group({
      empleado:['',Validators.required],
      d_basicos_devengadp:['', Validators.required],
      d_progresivos_dvengados:['', Validators.required],
    })
  }

  traer_empleados(){
    this.liq.traer_empleados().subscribe(res=>{
      if(res.estado=="success"){
        this.empleados = res.empleado;
      }
    });
  }
  listar_historial(){
    this._vacaciones.listar_historial().subscribe(res=>{
      if(res){
        this.tabla = res.lista;
      }
    })
  }

  open_modal(modal){

    this.ventana = this.modalService2.open(modal, { size: 'xl' });

  }

  enviar(){

    if(this.form.valid){
      const fd = new FormData();
      fd.append('empleado', this.form.controls.empleado.value );
      fd.append('d_basicos_devengadp', this.form.controls.d_basicos_devengadp.value );
      fd.append('d_progresivos_dvengados', this.form.controls.d_progresivos_dvengados.value );
      this._vacaciones.crear_historial(fd).subscribe(res => {
        if(res){
          this.listar_historial();
          alert(res.mensaje);
        }
      })
    }else{
      alert("Faltan campos por llenar");
    }
  }

  capturar(evento){
    this.comunicar = evento;
    console.log(evento);
  }
  capturar2(evento){
    this.comunicar = evento;
    console.log(evento);
  }

}