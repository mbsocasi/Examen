package com.cursos.cursos.models.entities;

import jakarta.persistence.*;
import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.Size;
import java.time.LocalDateTime;
import java.util.ArrayList;
import java.util.List;

@Entity
@Table(name = "cursos")
public class Cursos {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @NotBlank(message = "El nombre del curso es obligatorio")
    @Size(min = 3, max = 100, message = "El nombre debe tener entre 3 y 100 caracteres")
    @Column(nullable = false)
    private String nombre;

    @NotBlank(message = "La descripción es obligatoria")
    @Size(min = 10, max = 255, message = "La descripción debe tener entre 10 y 255 caracteres")
    @Column(nullable = false)
    private String descripcion;

    @OneToMany(cascade = CascadeType.ALL, orphanRemoval = true)
    @JoinColumn(name = "curso_id")
    private List<CurosMateriales> cursoMateriales = new ArrayList<>();

    @Column(nullable = false, updatable = false)
    private LocalDateTime creado;

    @PrePersist
    public void prePersist() {
        this.creado = LocalDateTime.now();
    }

    public Long getId() {
        return id;
    }

    public void setId(Long id) {
        this.id = id;
    }

    public String getNombre() {
        return nombre;
    }

    public void setNombre(String nombre) {
        this.nombre = nombre;
    }

    public String getDescripcion() {
        return descripcion;
    }

    public void setDescripcion(String descripcion) {
        this.descripcion = descripcion;
    }

    public List<CurosMateriales> getCursoMateriales() {
        return cursoMateriales;
    }

    public void setCursoMateriales(List<CurosMateriales> cursoMateriales) {
        this.cursoMateriales = cursoMateriales;
    }

    public LocalDateTime getCreado() {
        return creado;
    }
}
