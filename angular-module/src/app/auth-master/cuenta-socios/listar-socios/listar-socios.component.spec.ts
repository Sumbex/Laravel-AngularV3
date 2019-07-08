import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ListarSociosComponent } from './listar-socios.component';

describe('ListarSociosComponent', () => {
  let component: ListarSociosComponent;
  let fixture: ComponentFixture<ListarSociosComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ListarSociosComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ListarSociosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
